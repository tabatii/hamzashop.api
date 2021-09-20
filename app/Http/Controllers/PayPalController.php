<?php

namespace App\Http\Controllers;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\PaypalCaptureRequest;
use App\Http\Requests\PaypalCreateRequest;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;

class PayPalController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
	 * For demo purpose, we are using SandboxEnvironment. In production this will be
	 * ProductionEnvironment.
	 */
	public function environment()
	{
		$clientId = env('PAYPAL_ID') ?: 'PAYPAL-SANDBOX-CLIENT-ID';
		$clientSecret = env('PAYPAL_SECRET') ?: 'PAYPAL-SANDBOX-CLIENT-SECRET';
		return new SandboxEnvironment($clientId, $clientSecret);
	}

	/**
	 * Returns PayPal HTTP client instance with environment which has access
	 * credentials context. This can be used invoke PayPal API's provided the
	 * credentials have the access to do so.
	 */
	public function client()
	{
		return new PayPalHttpClient($this->environment());
	}

	public function body($data)
	{
		$product = Product::findOrFail($data['product']);
		$address = Address::findOrFail($data['address']);
		$shipping = Shipping::where('code', $address->country)->firstOrFail();
		$price = Currency::convert()->from('MAD')->to('USD')->amount(($product->price * $data['quantity']) + $shipping->price)->get();
		return [
			'intent' => 'CAPTURE',
			'application_context' => [
				'brand_name' => env('APP_NAME'),
				'shipping_preference' => 'NO_SHIPPING'
			],
			'purchase_units' => [
				[
					'description' => $product->title,
					'amount' => [
						'currency_code' => 'USD',
						'value' => round($price, 2)
					]
				]
			]
		];
	}

	public function create(PaypalCreateRequest $request)
	{
		$payment = new OrdersCreateRequest();
		$payment->prefer('return=representation');
		$payment->body = $this->body($request->validated());
		$response = $this->client()->execute($payment);
		return response()->json($response);
	}

	public function capture(PaypalCaptureRequest $request)
	{
		return DB::transaction(function () use ($request) {

			$address = Address::findOrFail($request->address);
			$shipping = Shipping::where('code', $address->country)->firstOrFail();
			$data = $this->client()->execute(new OrdersCaptureRequest($request->order));

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
			$order->paid_amount = $data->result->purchase_units[0]->payments->captures[0]->amount->value;
			$order->paid_currency = $data->result->purchase_units[0]->payments->captures[0]->amount->currency_code;
			$order->payment_method = Order::PAYPAL;
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
