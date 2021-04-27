<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use App\Http\Controllers\UsdExchangeController;
use App\Http\Requests\WithDateIntervalRequest;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Client;

class PaymentController extends BaseApiController
{

    // list of payments [amount/currency] due to date
    public function paymentsByDate(WithDateIntervalRequest $reqeust)
    {
        $input = $reqeust->validated();
        $result = Payment::select('amount', 'currency')->where('verified_at', '>=', $input['from'])->where('verified_at', '<', $input['to'])->get();
        return $this->sendResponse($result, 'Payments due to date retrieved successfully');
    }

    //avarage payments amount(USD) grouped by counry 
    public function paymentsByCounry() 
    {
        $payments = Payment::with('client')->get()->groupBy('client.country');
        foreach ($payments as $country => $countryPayments) {
            $avarage = [];
            foreach ($countryPayments as $payment) {
                $avarage[] = $payment->amount;
            }
            $result[$country] = intval(array_sum($avarage)/count($avarage));
        }
        return $this->sendResponse($result, 'Avarage income by counries retrieved successfully!');
    }
}
