<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display user statistics of the TRI P2P Wallet.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $walletBalance = Transaction::getWalletBalance();

        $totalSentMoney = Transaction::getTotalSentMoney();

        $totalReceivedMoney = Transaction::getTotalReceivedMoney();

        $topSender = Transaction::getTopSender();

        $thirdHighestTrxAmount = Transaction::getThirdHighestTrxAmount();

        $returnData = [
            'walletBalance' => $walletBalance,
            'totalSentMoney' => $totalSentMoney,
            'totalReceivedMoney' => $totalReceivedMoney,
            'topSender' => $topSender,
            'thirdHighestTrxAmount' => $thirdHighestTrxAmount
        ];

        return response()->instantResponse(true, null, null, $returnData, 200);
    }
}
