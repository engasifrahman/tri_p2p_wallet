<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\SendMail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\InitiateTransactionRequest;


class TransactionController extends Controller
{
    /**
     * Initiate a new send money transaction in storage.
     *
     * @param  \App\Http\Requests\InitiateTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function initiateSendMoney(InitiateTransactionRequest $request)
    {
        $receiver = User::where('email', $request->receiver_identity)
        ->orWhere('phone', $request->receiver_identity)
        ->first();

        if(!$receiver){
            return response()->instantResponse(false, 'Receiver\'s wallet not found!', null, null, 404);
        }

        if($receiver->id == auth()->user()->id){
            return response()->instantResponse(false, 'You can not send money to your own wallet!', null, null, 400);
        }

        $currencyFrom = auth()->user()->currency;
        $currencyTo = $receiver->currency;

        if($currencyFrom == $currencyTo){
            return response()->instantResponse(false, 'Receiver\'s wallet currency can not be the same as yours!', null, null, 400);
        }

        $walletBalance = Transaction::getWalletBalance();

        if($request->amount > $walletBalance){
            return response()->instantResponse(false, 'Insufficient balance!', null, null, 400);
        }

        $convertRespose = Transaction::convertCurrency($currencyFrom, $currencyTo, $request->amount);

        if(!$convertRespose['success']){
            if($convertRespose['status'] == 429){
                response()->setResponse(false, 'Currency converter\'s daily limit exceeded!', null, null, 500);
            } else{
                response()->setResponse(false, 'Currency converter\'s issue, please try again later!', null, null, 500);
            }
        } else{
            try {
                $transaction = Transaction::create([
                    'sender_id' => auth()->user()->id,
                    'receiver_id' => $receiver->id,
                    'sent_amount' => $request->amount,
                    'received_amount' => $convertRespose['converted_amount'],
                    'conversion_rate' => $convertRespose['rate']
                ]);

                $return_data = [
                    'transaction_id' => $transaction->id,
                    'receiver_name' => $receiver->name,
                    'receiver_identity' => $request->receiver_identity,
                    'sent_amount' => $request->amount,
                    'sent_currency' =>  $currencyFrom ,
                    'converted_amount' => $convertRespose['converted_amount'],
                    'converted_currency' => $currencyTo,
                    'conversion_rate' => $convertRespose['rate']
                ];

                response()->setResponse(true, 'Transaction initiated successfully!', null, $return_data, 200);
            } catch (\Exception $e) {
                response()->setResponse(false, 'Unable to proceed right now, please try again!', null, null, 500);
            }
        }

        return response()->getResponse();
    }

    /**
     * Complete a initiated send money transaction's status in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function completeSendMoney(Request $request, Transaction $transaction)
    {
        $validator = Validator::make($request->all(), [
            "status" => "required|in:success,cancel"
        ]);

        if($validator->fails()){
            return response()->instantResponse(false, 'Validation failed!', $validator->errors(), null, 422);
        }

        if(!$transaction){
            return response()->instantResponse(false, 'Something went wrong!', null, null, 400);
        }

        if($transaction->status !== Transaction::INITIATED){
            return response()->instantResponse(false, 'This transaction already completed!', null, null, 400);
        }

        $transaction->status = ($request->status === Transaction::SUCCESS) ? $request->status : Transaction::CANCELED;

        if($transaction->save()){
            if($transaction->status = Transaction::SUCCESS){
                SendMail::dispatch($transaction)->delay(now()->addSeconds(10));
            }

            $message = $request->status === Transaction::SUCCESS ? 'successful' : 'cancelled';
            response()->setResponse(true, "Transaction $message!", null, null, 200);
        } else{
            response()->setResponse(false, 'Unable to proceed right now, please try again!', null, null, 400);
        }

        return response()->getResponse();
    }

    /**
     * Display a listing of successfull transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function transactions()
    {
        $walletBalance = Transaction::getWalletBalance();

        $totalSentMoney = Transaction::getTotalSentMoney();

        $totalReceivedMoney = Transaction::getTotalReceivedMoney();

        $transactions = Transaction::where('status', Transaction::SUCCESS)
            ->where(function ($query) {
                $query->where('sender_id', auth()->user()->id)
                    ->orWhere('receiver_id', auth()->user()->id);
            })
            ->orderBy('updated_at', 'DESC')
            ->with('sender', 'receiver')
            ->get();

        $returnData = [
            'walletBalance' => $walletBalance,
            'totalSentMoney' => $totalSentMoney,
            'totalReceivedMoney' => $totalReceivedMoney,
            'transactions' => $transactions
        ];

        return response()->instantResponse(true, null, null, $returnData, 200);
    }

    /**
     * Display a listing of successfull sent money transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function sentMoneyTransactions()
    {
        $walletBalance = Transaction::getWalletBalance();

        $totalSentMoney = Transaction::getTotalSentMoney();

        $totalReceivedMoney = Transaction::getTotalReceivedMoney();

        $transactions = Transaction::where('status', Transaction::SUCCESS)
            ->where('sender_id', auth()->user()->id)
            ->orderBy('updated_at', 'DESC')
            ->with('sender', 'receiver')
            ->get();

        $returnData = [
            'walletBalance' => $walletBalance,
            'totalSentMoney' => $totalSentMoney,
            'totalReceivedMoney' => $totalReceivedMoney,
            'transactions' => $transactions
        ];

        return response()->instantResponse(true, null, null, $returnData, 200);
    }

    /**
     * Display a listing of successfull received money transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function receivedMoneyTransactions()
    {
        $walletBalance = Transaction::getWalletBalance();

        $totalSentMoney = Transaction::getTotalSentMoney();

        $totalReceivedMoney = Transaction::getTotalReceivedMoney();

        $transactions = Transaction::where('status', Transaction::SUCCESS)
            ->where('receiver_id', auth()->user()->id)
            ->orderBy('updated_at', 'DESC')
            ->with('sender', 'receiver')
            ->get();

        $returnData = [
            'walletBalance' => $walletBalance,
            'totalSentMoney' => $totalSentMoney,
            'totalReceivedMoney' => $totalReceivedMoney,
            'transactions' => $transactions
        ];

        return response()->instantResponse(true, null, null, $returnData, 200);
    }
}
