<?php

namespace App\Http\Controllers;

include("vendor/autoload.php");
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

use Illuminate\Http\Request;

use App\Http\Requests;


class FawzyController extends Controller
{

    public function test(Request $request){

        $requestPayments =[
            'request_id' => 10,
            'payment_mode' => 'CASH',
            'distance' => 10,
            'discount_wallet' => 10,
            'WaitingTime' => 10,
            'WaitingPrice' => 10,
            'price' => 10,
            'fixed' => 10,
            'tax' => 10,
            'min_wait_price' => 10,
            'wallet' => 10,
            'time_trip' => 10,
            'time_trip_price' => 10,
            'before_discount_total' => 10,
            'total' => 100,
        ];

        return $request;
    }

    public function testServer(){
        return ["msg"=>"Aloo"];
    }

    public function socket(){
        return "Alo";
        
        try{
          
            $client = new \Guzzle\Service\Client('http://192.168.1.220:3000/alo');
            $response = $client->get()->send();
            return $response;

        }catch(Throwable $e){
            return $e;
        }
        return Done;

    }

}
