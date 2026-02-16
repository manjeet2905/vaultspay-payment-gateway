<?php
namespace Vp\VaultsPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class VaultsPayController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Browser Redirect (redirectUrl)
    |--------------------------------------------------------------------------
    | User lands here after payment
    | This page only displays status
    */
    // public function result(Request $request)
    // {
    //     return view('vaultspay::result', [
    //         'reference' => $request->ref
    //     ]);
    // }
    public function result($reference)
    {
        $paymentId = Cache::get('vaultspay_' . $reference);

        if (! $paymentId) {
            return response()->json([
                'status'  => 'INVALID',
                'message' => 'Payment reference expired',
            ], 404);
        }

        $client = app('vaultspay')->make();

        // wait until gateway finishes processing
        for ($i = 0; $i < 10; $i++) {

            $result = $client->verify($paymentId);

            if ($result['status'] !== 'PENDING') {
                return response()->json($result);
            }

            sleep(2);
        }

        return response()->json([
            'status'  => 'PENDING',
            'message' => 'Payment still processing',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VaultsPay Server Callback (callBackUrl)
    |--------------------------------------------------------------------------
    | This is the REAL payment confirmation
    */
    public function callback(Request $request)
    {
        $transactionId = $request->input('vpTransactionId');
        $status        = strtoupper($request->input('status', 'PENDING'));

        if (! $transactionId) {
            return response()->json(['error' => 'missing transaction id'], 400);
        }

        // store globally
        cache()->put('vaultspay_' . $transactionId, $status, now()->addMinutes(30));

        // also store latest transaction for session user
        session(['vaultspay_last_transaction' => $transactionId]);

        return response()->json(['received' => true]);
    }

    public function status($reference)
    {
        $paymentId = Cache::get('vaultspay_' . $reference);

        if (! $paymentId) {
            return response()->json(['status' => 'INVALID']);
        }

        $client = app('vaultspay')->make();
        return response()->json($client->verify($paymentId));
    }

}
