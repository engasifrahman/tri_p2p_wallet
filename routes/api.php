<?php

use App\Models\User;
use App\Jobs\SendMail;
use App\Mail\ReceivedMoney;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function (){
    Route::post('/registration', 'AuthController@registration')->name('registration');
    Route::post('/login', 'AuthController@login')->name('login');

    Route::middleware(['auth:sanctum'])->group( function (){
        Route::get('/statistics', 'WalletController@statistics')->name('statistics');

        Route::get('/transactions', 'TransactionController@transactions')->name('transactions');
        Route::get('/sent-money-transactions', 'TransactionController@sentMoneyTransactions')->name('sent_money_transactions');
        Route::get('/received-money-transactions', 'TransactionController@receivedMoneyTransactions')->name('received_money_transactions');

        Route::post('/initiate-send-money', 'TransactionController@initiateSendMoney')->name('initiate_send_money');
        Route::put('/complete-send-money/{transaction}', 'TransactionController@completeSendMoney')->name('complete_send_money')->can('complete-send-money', 'transaction');;

        Route::get('/logout', 'AuthController@logout')->name('logout');
    });
});
