<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    public function process(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitize');
        Config::$is3ds = config('midtrans.enable_3ds');

        $orderId = 'order_' . rand();  
        $gross = rand(1, 10) * 10000;  
        $userId = rand(1, 4);  

        // Menyiapkan parameter untuk Midtrans Snap API
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $gross,
            ],
            'customer_details' => [
                'user_id' => $userId,
            ],
        ];

        $order = Order::create([
            'order_id' => $orderId,
            'total_amount' => $gross,
            'status' => 'pending',  
            'user_id' => $userId,
        ]);

        $transaction = Transaction::create([
            'order_id' => $orderId, 
            'transaction_id' => 'transaction_' . $orderId,
            'payment_type' => 'bank_transfer',
            'gross_amount' => $gross,
            'transaction_status' => 'pending', 
            'transaction_time' => Carbon::now(),
        ]);

        $snapToken = Snap::getSnapToken($params);

        return view('payment', compact('snapToken'));
    }

    // Callback untuk menangani hasil transaksi dari Midtrans
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            $serverKey
        );

        if ($signatureKey != $request->signature_key) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $transaction = Transaction::updateOrCreate(
            ['order_id' => $request->order_id],
            [
                'transaction_id' => $request->transaction_id,
                'payment_type' => $request->payment_type,
                'gross_amount' => $request->gross_amount,
                'transaction_status' => $request->transaction_status,
                'transaction_time' => $request->transaction_time,
            ]
        );

        if ($transaction->transaction_status == 'settlement') {
            $order = Order::where('order_id', $request->order_id)->first();
            if ($order) {
                $order->status = 'paid';
                $order->save();
            }
        }

        Log::info('🔥 Midtrans Callback Masuk:', $request->all());

        return response()->json(['message' => 'OK'], 200);
    }
}
