<?php

namespace App\Http\Controllers\api;

use Omnipay\Omnipay;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CreditController extends Controller
{
    /**
     * Get the count of credits for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCreditsCount(Request $request)
    {
        // Ensure user is authenticated
        $user = $request->user();

        if (!$user) {
            return $this->respondUnauthorized();
        }

        // Retrieve and return the count of credits
        $creditsCount = $user->credits;

        return response()->json(['credits_count' => $creditsCount], 200);
    }

     /**
     * Get the last ten purchased credits for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastTenPurchasedCredits(Request $request)
    {
        // Ensure user is authenticated
        $user = $request->user();
        if (!$user) {
            return $this->respondUnauthorized();
        }

        // Retrieve the last ten purchased credits
        $lastTenPayments = Payment::where('user_id', $user->id)->where('status', 'completed')->latest()->take(10)->get();

        return response()->json(['purchase_history' => $lastTenPayments], 200);
    }

    public function purchaseTaskCredits(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();
        if (!$user) {
            return $this->respondUnauthorized();
        }
        // Validate request data
       $validator = Validator::make($request->all(), [
            'number_of_credits' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return response()->json(['errors' => $errors, 'status' => false], 422);
        }

        // Calculate the total price based on the number of credits and your configured price
        $pricePerCredit = config('price_of_credit');

        $totalPrice = $request->input('number_of_credits') * $pricePerCredit;
        // Create a unique reference number
        $refNo = uniqid('payment_');
        // Check if payment should be bypassed based on config
        $bypassPayment = config('app.bypass_payment');

        // If payment is bypassed, simulate successful purchase
        if ($bypassPayment) {

            // Simulate successful payment
            $payment = Payment::create([
                'user_id' => $user->id,
                'no_of_credits' => $request->input('number_of_credits'),
                'ref_no' => $refNo,
                'status' => 'completed',
            ]);
            
            // Increase the user's credits
            $user->increment('credits', $request->input('number_of_credits'));

            // Return success response
            return response()->json(['success' => true, 'payment_id' => $payment->id,'Creduit_amount' => $totalPrice, 'Number_of_credit' => $request->input('number_of_credits') ,'message' => 'Credits Successfully Purchased!']);
           
        } else {
            // Validate request data
            $request->validate([
                'cardHolderName' => 'required|string',
                'stripeToken' => 'required|string',
                // Add any other validations for additional payment data

            ]);

            // Initialize the Omnipay Stripe gateway
            $gateway = Omnipay::create('Stripe');
            $gateway->setApiKey(env('STRIPE_SECRET_KEY'));

            // Purchase using Omnipay
            $response = $gateway->purchase([
                'amount' => $totalPrice,
                'currency' => env('STRIPE_CURRENCY'),
                'description' => 'Task Credits Purchase',
                'token' => $request->input('stripeToken'),
                'card' => [
                    'name' => $request->input('cardHolderName'),
                ],
            ])->send();

            // Check if the payment was successful
            if ($response->isSuccessful()) {
                // Save payment information to your database
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'no_of_credits' => $request->input('number_of_credits'),
                    'ref_no' => $refNo,
                    'status' => 'completed',
                ]);

                // Increase the user's credits
                $user->increment('credits', $request->input('number_of_credits'));

                // Return success response
                return response()->json(['success' => true, 'payment_id' => $payment->id]);
            } else {
                // Handle failed payment
                return response()->json(['error' => $response->getMessage()], 500);
            }
        }


    }

    private function respondUnauthorized()
    {
        return response()->json(['error' => 'Unauthenticated, kindly try again after authentication'], Response::HTTP_UNAUTHORIZED);
    }
}

