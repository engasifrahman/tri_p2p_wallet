<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    const INITIATED = 'initiated';
    const SUCCESS = 'success';
    const FAILED = 'failed';
    const CANCELED = 'cancelled';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'status'
    ];

    /**
     * Get the user who owns the send money transaction.
     */
    public function sender(){
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    /**
     * Get the user who owns the received money transaction.
     */
    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }


    public static function getWalletBalance(){
        $transaction = Transaction::selectRaw('sum(case when receiver_id = ? then received_amount else - sent_amount end) as balance', [auth()->user()->id])
            ->where(function ($query) {
                $query->where('sender_id', auth()->user()->id)
                    ->orWhere('receiver_id', auth()->user()->id);
            })
            ->where('status', Transaction::SUCCESS)
            ->first();

        return $transaction->balance != null ? round(auth()->user()->initial_balance + $transaction->balance, 2) : auth()->user()->initial_balance;
    }

    public static function getTotalSentMoney(){
        $total_sent_money = Transaction::where('sender_id', auth()->user()->id)
            ->where('status', Transaction::SUCCESS)
            ->sum('sent_amount');

        return $total_sent_money ? round($total_sent_money, 2) : 0;
    }

    public static function getTotalReceivedMoney(){
        $total_received_money = Transaction::where('receiver_id', auth()->user()->id)
            ->where('status', Transaction::SUCCESS)
            ->sum('received_amount');

        return $total_received_money ? round($total_received_money, 2) : 0;
    }

    public static function getTopSender(){
        $top_sender = Transaction::where('status', Transaction::SUCCESS)
            ->selectRaw('sum(sent_amount) as total_sent_money, sender_id')
            ->groupBy('sender_id')
            ->orderBy('total_sent_money', 'DESC')
            ->with('sender')
            ->first();

        return $top_sender ? $top_sender : 'N/A';
    }

    public static function getThirdHighestTrxAmount(){
        $third_highest_trx_ammount = Transaction::where('sender_id', auth()->user()->id)
            ->where('status', Transaction::SUCCESS)
            ->orderByRaw('CAST(sent_amount as UNSIGNED) DESC')
            ->skip(2)
            ->take(1)
            ->first();

        return $third_highest_trx_ammount ? $third_highest_trx_ammount->sent_amount : 'N/A';
    }

    public static function convertCurrency($from, $to, $amount){
        $returnData = [
            'success' => false,
            'status' => null,
            'rate' => null,
            'converted_amount' => 0
        ];

        try {
            $response = Http::acceptJson()->get('https://api.apilayer.com/currency_data/convert', [
                'to' => $to,
                'from' => $from,
                'amount' => $amount,
                // 'apikey' => 'XNHuBwGEbY85uMQbKPTN16QGWeihg6Qc'
                'apikey' => 'hAHmJ9XC2bisktn2IYiYG9Ovn4UiskRt'
            ]);

            $returnData['status'] = $response->status();

            if($response->successful()){
                $returnData['success'] = true;
                $returnData['rate'] = $response?->json()['info']['quote'];
                $returnData['converted_amount'] = round($response['result'], 2);
            }
        } catch (\Exception $e) {
            //
        }

        return $returnData;
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->diffForHumans();
    }
}
