<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as Http;
use GuzzleHttp\Exception\ClientException;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\CreditCardRequest;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;

class TwoCheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function auth()
    {
        $code = env('2CHECKOUT_CODE');
        $key = env('2CHECKOUT_SECRET');
        $date = gmdate('Y-m-d H:i:s');
        $string = strlen($code).$code.strlen($date).$date;
        $hash = hash_hmac('md5', $string, $key);
        return "code=\"{$code}\" date=\"{$date}\" hash=\"{$hash}\"";
    }

    public function config()
    {
        return [
            'base_uri' => 'https://api.2checkout.com/rest/6.0/',
            'headers' => [
                'X-Avangate-Authentication' => $this->auth()
            ]
        ];
    }

    public function body($data)
    {
        $product = Product::findOrFail($data['product']);
        $address = Address::findOrFail($data['address']);
        $shipping = Shipping::where('code', $address->country)->firstOrFail();
        $price = Currency::convert()->from('MAD')->to('USD')->amount(($product->price * $data['quantity']) + $shipping->price)->get();
        $date = str_split($data['date'], 2);
        return [
            'Items' => [
                [
                    'Name' => $product->short_title,
                    'PurchaseType' => 'PRODUCT',
                    'IsDynamic' => true,
                    'Tangible' => true,
                    'Quantity' => 1,
                    'Code' => null,
                    'Price' => [
                        'Type' => 'CUSTOM',
                        'Amount' => $price,
                    ]
                ]
            ],
            'BillingDetails' => [
                'Email' => auth()->user()->email,
                'FirstName' => $address->first_name,
                'LastName' => $address->last_name,
                'CountryCode' => $address->country,
                'Address1' => $address->street,
                'City' => $address->city,
                'Zip' => $address->zip,
            ],
            'PaymentDetails' => [
                'Currency' => 'USD',
                'Type' => 'CC',
                'PaymentMethod' => [
                    'Vendor3DSReturnURL' => 'www.return.com',
                    'Vendor3DSCancelURL' => 'www.cancel.com',
                    'CardType' => $data['type'],
                    'HolderName' => $data['name'],
                    'CardNumber' => $data['card'],
                    'ExpirationYear' => '20'.$date[1],
                    'ExpirationMonth' => $date[0],
                    'CCID' => $data['cvv'],
                ]
            ]
        ];
    }

    public function checkout(CreditCardRequest $request)
    {
        $http = new Http($this->config());
        try {
            $response = $http->post('orders', [
                'json' => $this->body($request->validated())
            ]);
            return DB::transaction(function () use ($request, $response) {

                $address = Address::findOrFail($request->address);
                $shipping = Shipping::where('code', $address->country)->firstOrFail();
                $data = json_decode($response->getBody()->getContents(), true);

                $product = Product::findOrFail($request->product);
                $product->stock -= 1;
                $product->save();

                $order = new Order;
                $order->user_id = auth()->id();
                $order->product_id = $product->id;
                $order->address_id = $address->id;
                $order->quantity = $request->quantity;
                $order->unit_price = $product->price;
                $order->shipping_price = $shipping->price;
                $order->total_amount =($product->price * $request->quantity) + $shipping->price;
                $order->paid_amount = $data['GrossPrice'];
                $order->paid_currency = $data['PayoutCurrency'];
                $order->payment_method = $data['PaymentDetails']['PaymentMethod']['CardType'];
                $order->status = Order::PENDING;
                $order->save();

                $notification = new Notification;
                $notification->icon = 'mdi-cart-plus';
                $notification->content = (new NotificationService)->newOrder();
                $notification->save();

                return response()->json();
            });
        } catch (ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents());
            return response()->json($error, 400);
        }
    }
}
