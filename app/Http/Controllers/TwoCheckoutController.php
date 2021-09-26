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

    public function body($data, $token)
    {
        $product = Product::findOrFail($data['product']);
        $address = Address::findOrFail($data['address']);
        $shipping = Shipping::where('code', $address->country)->firstOrFail();
        $price = Currency::convert()->from('MAD')->to('USD')->amount(($product->price * $data['quantity']) + $shipping->price)->get();
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
                'Type' => 'EES_TOKEN_PAYMENT',
                'Currency' => 'USD',
                'PaymentMethod' => [
                    'Vendor3DSReturnURL' => 'www.return.com',
                    'Vendor3DSCancelURL' => 'www.cancel.com',
                    'EesToken' => json_decode($token, true)['Results']['Token'],
                ]
            ]
        ];
    }

    public function checkout(CreditCardRequest $request)
    {
        $http = new Http($this->config());
        $date = str_split($request->date, 2);
        try {
            $tokenResponse = $http->post('tokens', [
                'json' => [
                    'Name' => $request->name,
                    'CreditCard' => $request->card,
                    'ExpirationDate' => $date[0].'/'.$date[1],
                    'Cvv' => $request->cvv,
                ]
            ])->getBody()->getContents();
            $paymentResponse = $http->post('orders', [
                'json' => $this->body($request->validated(), $tokenResponse)
            ]);
        } catch (ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents());
            return response()->json($error, 400);
        }
    }

    public function order($data)
    {
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
    }
}
