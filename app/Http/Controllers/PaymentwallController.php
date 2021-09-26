<?php

namespace App\Http\Controllers;

use Paymentwall_Config;
use Paymentwall_Charge;
use Illuminate\Support\Facades\DB;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\PaymentwallRequest;
use App\Services\NotificationService;
use App\Models\Notification;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;

class PaymentwallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payment(PaymentwallRequest $request)
    {
        Paymentwall_Config::getInstance()->set([
            'public_key' => '5cc0da785d103032ce86c8dbb4334bcd',
            'private_key' => '23d718a481d13292810994bcc4475061'
        ]);

        $charge = new Paymentwall_Charge;
        $charge->create([
            'token' => $request->token,
            'fingerprint' => $request->fingerprint,
            'email' => auth()->user()->email,
            'currency' => 'USD',
            'amount' => 100,
            'description' => 'Order #123'
        ]);

        return response()->json();
    }
}
