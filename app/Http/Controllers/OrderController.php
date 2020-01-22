<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Services\QuadXAPIService;

class OrderController extends Controller
{
    private $quadXApi;

    /**
     * @return constructor
     */
    public function __construct(QuadXAPIService $quadXApi){
        $this->quadXApi = $quadXApi;
    }

    /**
     * @return base url / landing page
     */
    public function index(){
        #call get all orders api
        $orders = $this->quadXApi->getOrdersAPI();
        #call countries
        $countries = $this->quadXApi->getCountries();
        return view('index', compact('orders', 'countries'));
    }


    /**
     * @return create order
     */
    public function create(Request $request){
        $errors = [];
        $status = 500;
        $validations = [
            'buyer_name'    =>  'required',
            'email'    =>  'required|email',
            'contact_number'    =>  'required',
            'contact_number'    =>  'required',
            'delivery_name' => 'required',
            'delivery_email' => 'required',
            'delivery_shipment' => 'required',
            'delivery_line_1' => 'required',
            'delivery_city' => 'required',
            'delivery_state' => 'required',
            'delivery_postal_code' => 'required',
            'delivery_country' => 'required',
        ];

        #if status is pending
        if($request->status == 'for_pickup'){
            $validations['pickup_name'] = 'required';
            $validations['pickup_email'] = 'required';
            $validations['pickup_shipment'] = 'required';
            $validations['pickup_line_1'] = 'required';
            $validations['pickup_city'] = 'required';
            $validations['pickup_state'] = 'required';
            $validations['pickup_postal_code'] = 'required';
            $validations['pickup_country'] = 'required';
        }

        $validator = Validator::make($request->all(), $validations);

        #check if validation success
        if(!$validator->fails()){ 
            #call create api services
            $status = $this->quadXApi->createOrderAPI($request->all());
        }

        return response()->json(['errors' => $validator->messages(), 'status' => $status]);
    }

    /**
     * @return states, cities
     */
    public function country(Request $request){
        #call get all cities by country api
        $cities = $this->quadXApi->getCitiesAPI($request->country);
        #call get all states by country api
        $states = $this->quadXApi->getStatesAPI($request->country);

        return response()->json(['cities' => $cities, 'states' => $states]);
    }
}