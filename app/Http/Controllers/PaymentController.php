<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    // public function notification(Request $request)
    // {
    //     // $serverKey = config('midtrans.server_key');
    //     $serverKey = env('MIDTRANS_SERVER_KEY');
    //     $signatureKey = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

    //     if ($signatureKey !== $request->signature_key) {
    //         return response('Invalid signature', 403);
    //     }

    //     // Proses status seperti capture, settlement, pending, cancel, etc.
    //     if ($request->transaction_status === 'settlement') {
    //         // update payment status jadi paid
    //     }

    //     return response('OK', 200);
    // }

    public function notification(Request $request)
    {
        $data = $request->all(); // atau gunakan json_decode
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // Validasi signature
        $signature = hash('sha512', 
            $data['order_id'] . 
            $data['status_code'] . 
            $data['gross_amount'] . 
            $serverKey
        );

        if ($signature !== $data['signature_key']) {
            Log::warning('Midtrans callback: Invalid signature', $data);
            return response('Invalid signature', 403);
        }

        Log::info('Midtrans callback received', $data);

        if ($data['transaction_status'] === 'settlement') {
            // update payment status
            // Contoh: Order::where('order_id', $data['order_id'])->update(['status' => 'paid']);
        }

        return response('OK', 200);
    }

    public function charge(Request $request)
    {
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@example.com',
                'phone' => '08123456789',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

