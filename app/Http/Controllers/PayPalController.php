<?php

namespace App\Http\Controllers;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\PaypalCaptureRequest;
use App\Http\Requests\PaypalCreateRequest;
use App\Services\AmanaService;
use App\Models\Product;
use App\Models\Order;

class PayPalController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Set up and return PayPal PHP SDK environment with PayPal access credentials.
	 * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
	 */
	public function environment()
	{
		$clientId = env('PAYPAL_ID') ?: 'PAYPAL-SANDBOX-CLIENT-ID';
		$clientSecret = env('PAYPAL_SECRET') ?: 'PAYPAL-SANDBOX-CLIENT-SECRET';
		return new ProductionEnvironment($clientId, $clientSecret);
	}

	/**
	 * Returns PayPal HTTP client instance with environment that has access
	 * credentials context. Use this instance to invoke PayPal APIs, provided the
	 * credentials have access.
	 */
	public function client()
	{
		return new PayPalHttpClient($this->environment());
	}

	public function body($id, $qty)
	{
		$product = Product::findOrFail($id);
		$amana = (new AmanaService)->price($product->weight);
		$price = Currency::convert()->from('MAD')->to('USD')->amount(($product->price * $qty) + $amana)->get();
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
		$payment->body = $this->body($request->product, $request->quantity);
		$response = $this->client()->execute($payment);
		return response()->json($response);
	}

	public function capture(PaypalCaptureRequest $request)
	{
		$product = Product::findOrFail($request->product);
		$amana = (new AmanaService)->price($product->weight);
		$capture = new OrdersCaptureRequest($request->order);
		$data = $this->client()->execute($capture);

		$order = new Order;
		$order->user_id = auth()->id();
		$order->product_id = $product->id;
		$order->address_id = $request->address;
		$order->quantity = $request->quantity;
		$order->unit_price = $product->price;
		$order->shipping_price = $amana;
		$order->total_amount =($product->price * $request->quantity) + $amana;
		$order->paid_amount = $data->result->purchase_units[0]->payments->captures[0]->amount->value;
		$order->paid_currency = $data->result->purchase_units[0]->payments->captures[0]->amount->currency_code;
		$order->payment_method = Order::PAYPAL;
		$order->status = Order::PENDING;
		$order->save();
		return response()->json();
	}
}
