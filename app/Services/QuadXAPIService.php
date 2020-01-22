<?php

namespace App\Services;
use GuzzleHttp\Client;

class QuadXAPIService{

    private $client;
    private $headers;

    public function __construct(){
        config(['apiURL' => 'https://api.staging.quadx.xyz']);
        config(['contentType' => 'application/json']);
        config(['apiToken' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMTgzNDVlMjdlNGMxYmZmOWYiLCJ1dWlkIjoibVV1TW9Hdlc4MlZuTmptUEhOTnhzdlRxa2lYMiIsImlhdCI6MTU3OTY3MzA1OH0.xrN-lZDYvlQ48-ZhWKe3PDTS1D7J7lcZXEB-fKldhJI']);

        $this->client = new \GuzzleHttp\Client(['base_uri' => config('apiURL'), 'verify' => false]);
        $this->headers = [
            'Content-Type'=> config('contentType'), 
            'Authorization' => config('apiToken')
        ]; 
    }

    /**
     * @return call create order    
     */
    public function createOrderAPI($request){
        
        $total = 0;
        $ctr = 0;
        $ctr2 = 0;
        $request = (object) $request;
        $response = '';
       
        #get total amount 
        foreach($request->amount as $a){
            if($a != "" && $request->quantity[$ctr] != ""){
                $quantityAmount = $a * $request->quantity[$ctr];
                $total += $quantityAmount;
            }
            $ctr++;
        }

        $data = [
            'currency' => $request->currency,
            'total' => number_format($total, 2, '.', ''),
            'payment_method' => $request->payment_method,
            'payment_provider' => $request->payment_provider,
            'status' => $request->status,
            'buyer_name' => $request->buyer_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'delivery_address' => [
                'name' => $request->delivery_name,
                'email' => $request->delivery_email,
                'line_1' => $request->delivery_line_1,
                'city' => $request->delivery_city,
                'state' => $request->delivery_state,
                'postal_code' => $request->delivery_postal_code,
                'country' => $request->delivery_country,
            ]
        ];

        #if status is for_pickup
        if($request->status == 'for_pickup'){
            $data['pickup_address'] =  [
                'name' => $request->pickup_name,
                'email' => $request->pickup_email,
                'line_1' => $request->pickup_line_1,
                'city' => $request->pickup_city,
                'state' => $request->pickup_state,
                'postal_code' => $request->pickup_postal_code,
                'country' => $request->pickup_country,
            ];
        }

        #items
        $data['items'] = [];
        if(isset($request->type)){
            foreach($request->type as $t){
                if($request->description[$ctr2] != "" && $request->amount[$ctr2] != "" && $request->quantity[$ctr2] !=""){
                    $data['items'][] = [
                        'type' => $t,
                        'description' => $request->description[$ctr2],
                        'amount' => $request->amount[$ctr2],
                        'quantity' => $request->quantity[$ctr2],
                    ];
                }
                $ctr2++;
            }
        }
       
        try{
            $response = $this->client->post('v2/orders',['headers' => $this->headers, 'json'=> $data]);
            $response = $response->getStatusCode();
        }catch(\Exception $e){
            $response = 500;
        }
            
        return $response;
    }

    /**
     * @return get all orders
     */
    public function getOrdersAPI(){
        $response = '';

        try{
            $response = $this->client->get('v2/orders',['headers' => $this->headers]);
            $response = $response->getBody()->getContents();
            $response = json_decode($response)->data;
        }catch(\Exception $e){
            $response = 500;
        }
            
        return $response;
    }

    /**
     * @return get all countries
     */
    public function getCountries(){
        $response = '';

        try{
            $response = $this->client->get('v2/ams/locations/countries',['headers' => $this->headers]);
            $response = $response->getBody()->getContents();
            $response = json_decode($response)->data;
        }catch(\Exception $e){
            $response = 500;
        }
            
        return $response;
    }

     /**
     * @return get all cities per country
     */
    public function getCitiesAPI($country){
        $response = '';

        try{
            $response = $this->client->get('v2/ams/locations/countries/'.$country.'/cities',['headers' => $this->headers]);
            $response = $response->getBody()->getContents();
            $response = json_decode($response)->data;
        }catch(\Exception $e){
            $response = 500;
        }
            
        return $response;
    }

     /**
     * @return get all states per country
     */
    public function getStatesAPI($country){
        $response = '';

        try{
            $response = $this->client->get('v2/ams/locations/countries/'.$country.'/provinces',['headers' => $this->headers]);
            $response = $response->getBody()->getContents();
            $response = json_decode($response)->data;
        }catch(\Exception $e){
            $response = 500;
        }
            
        return $response;
    }
}