<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use App\Http\Controllers\UsdExchangeController;
use App\Http\Requests\WithDateIntervalRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Employer;
use App\Models\Payment;
use Carbon\Carbon;

class EmployerController extends BaseApiController
{
    // list of clients and their payments due to date grouped by employer
    public function clientsWithPayment(WithDateIntervalRequest $request)
    {
        $input = $request->validated();

        $employers = Employer::whereHas('payments', function (Builder $query) use ($input) {
            return $query->where('verified_at', '>=', $input['from'])->where('verified_at', '<', $input['to']);
        })->with(array('payments'=>function($query) use ($input){
            return $query->whereDate('verified_at', '>=', $input['from'])->whereDate('verified_at', '<', $input['to'])->with('client');
        }))->get();

        //return $this->sendResponse($employers, 'Clients and their payments due to date grouped by employer retrieved successfully');

        // simplify for readability
        foreach ($employers as $e) {
            $result[$e->name] = [
                'position' => $e->position
            ];
            foreach ($e->payments as $p) {
                $result[$e->name]['client-payments'][] = [
                    'client' => $p->client->name,
                    'amount' => $p->amount,
                    'currency' => $p->currency
                ];
            }
        }

        return $this->sendResponse($result, 'Clients and their payments due to date grouped by employer retrieved successfully');
    }

     //list of full wage of employers grouped by month
    public function wagesByMonth()
    {
        foreach (Employer::all() as $e) {
            //array of all employers payments grouped by month
            $payments = $e->payments()->get()->groupBy(function($val) {
                return Carbon::parse($val->verified_at)->format('Y-m');
            });

            // go throu each month of employers payments
            foreach ($payments as $monthName => $month) {
                $bonus = 0;
                //go throu each payment in month
                foreach ($month as $payment) {
                    // find out wage bonus
                    $bonus += $e->wage * $this->getBonusFromPayment($payment->amount, $payment->currency);
                }
                $result[$monthName][$e->name] = [
                    'position' => $e->position,
                    'wage' => $e->wage,
                    'bonus' => intval($bonus,2),
                    'total wage' => intval($e->wage + $bonus)
                ];
            }
        }
        return $this->sendResponse($result, 'Employers full wages by month retrieved successfully');
    }

    // get wage multiplayer from paymant amount
    private function getBonusFromPayment($amount, $currency) 
    {
        // exchange amount to usd if it is not usd
        if ($currency!= 'USD') {
            $amount = UsdExchangeController::convertToUsd($amount, $currency);
        }
        // get the bonus persent
        if ($amount>=250) {
            if ($amount>=1000) {
                if ($amount>=2000) {
                    if ($amount>=5000) {
                        return 0.2;
                    }
                    return 0.15;
                }
                return 0.1;
            }
            return 0.05;
        }
        return 0;
    }

    // get average full wage of all employers due to date
    public function avarageWagesByDate(WithDateIntervalRequest $request) 
    {
        $input = $request->validated();

        foreach (Employer::all() as $e) {
            // paymants made with employer within given time period
            $payments = $e->payments()->where('verified_at', '>=', $input['from'])->where('verified_at', '<', $input['to'])->get();

            // go throu each payment and calculate bonus
            $bonus = 0;
            foreach ($payments as $payment) {
                $bonus += $e->wage * $this->getBonusFromPayment($payment->amount, $payment->currency);
            }
            $wages[] = intval($e->wage+$bonus);
        }
        // calculate avarage from array of wages
        $result = array_sum($wages)/count($wages);
        return $this->sendResponse($result, 'Employers avarage full wage within time period retrieved successfully');
    }
}
