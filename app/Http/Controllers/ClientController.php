<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use App\Http\Requests\WithDateIntervalRequest;
use Illuminate\Http\Request;
use App\Models\Employer;
use App\Models\Client;
use App\Models\Payment;

class ClientController extends BaseApiController
{
    // get list of clients who made a paymend due to date (include payment amount)
    public function clientsWithPayment(WithDateIntervalRequest $request) 
    {
        $input = $request->validated();

        $clients = Client::whereHas('payments', function ($query) use ($input) {
            return $query->whereDate('verified_at', '>=', $input['from'])->whereDate('verified_at', '<', $input['to']);
        })->with(array('payments'=>function($query) use ($input){
            return $query->whereDate('verified_at', '>=', $input['from'])->whereDate('verified_at', '<', $input['to']);
        }))->get();

        //return $this->sendResponse($clients, 'Clients whick made payment within time period retrieved successfully');

        // simplify for readability
        $result['clients_amount'] = $clients->count();
        foreach ($clients as $c) {
            foreach ($c->payments as $p) {
                $result['clients'][$c->name]['payments'][] = [
                    'amount' => $p->amount,
                    'currency' => $p->currency
                ];
            }
        }
        return $this->sendResponse($result, 'Clients whick made payment within time period retrieved successfully');

    }

    // amount of clients who make more than one payment with all employers
    public function clients() 
    {
        // client which have amount of payments >= than amount of employers
        $clients = Client::withCount('payments')->having('payments_count', '>=', Employer::count())->get();
        
        $allEmployers = Employer::pluck('id');
        
        $result['amount'] = 0;
        foreach ($clients as $client) {
            $allClientEmployers = $client->payments->pluck('employer_id');
            if ( $allEmployers->intersect($allClientEmployers)->count() === $allEmployers->count() ) {
                $result['clients'][] = [
                    'name' => $client->name
                ];
                $result['amount']++;
            }
        }

        return $this->sendResponse($result, 'Clients which made more then one payments with each employer retrieved successfully');
    }
}
