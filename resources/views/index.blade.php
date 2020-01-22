@extends('layouts.app')
@section('title', 'API EXAM')
@section('content')
    <div class="main-container mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                   <h3 class="float-left">Orders</h3>
                    <div class="float-none"></div><br><br>
                    <table id="orderstb" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Tracking Number</th>
                                <th>Buyer Name</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($orders)
                                @foreach($orders as $or)
                                    <tr>
                                        <td>{{ $or->id }}</td>
                                        <td>{{ $or->tracking_number }}</td>
                                        <td>{{ $or->buyer_name }}</td>
                                        <td>{{ ucwords(str_replace("_", " ", $or->status)) }}</td>
                                        <td>{{ $or->created_at }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <br>
            <hr>    
            <form action="{{ url('orders/create') }}" id="order-create-form" method="POST">
                <div class="row mt-5">
                    <div class="col-md-8 col-sm-12">
                        <h3>Add Order</h3>
                        <div class="form-group">
                            <label>Buyer name*:</label>
                            <input type="text" class="form-control" placeholder="Buyer name " name="buyer_name">
                            <small class="form-text text-danger help-block buyer_name_error"></small>
                        </div>
                        <div class="form-group">
                            <label>Email*:</label>
                            <input type="email" class="form-control" placeholder="Email " name="email">
                            <small class="form-text text-danger help-block email_error"></small>
                        </div>
                        <div class="form-group">
                            <label>Contact number*:</label>
                            <input type="text" class="form-control" placeholder="Contact number " name="contact_number">
                            <small class="form-text text-danger help-block contact_number_error"></small>
                        </div>
                        <div class="form-group">
                            <label>Payment method*:</label>
                            <input type="text" class="form-control" value="cod" name="payment_method" readonly>
                            <small class="form-text text-danger help-block payment_method_error"></small>
                        </div>
                        <div class="form-group">
                            <label>Currency*:</label>
                            <input type="text" class="form-control" value="PHP" name="currency" readonly>
                            <small class="form-text text-danger help-block currency_error"></small>
                        </div>
                        <div class="form-group">
                            <label>Payment provider*:</label>
                            <input type="text" class="form-control" value="lbcx" name="payment_provider" readonly>
                            <small class="form-text text-danger help-block payment_provider_error"></small>
                        </div>
                        <div class="form-group">
                            <label>Status*:</label>
                            <select class="form-control" name="status" id="status">
                                <option value="pending">Pending</option>
                                <option value="for_pickup">For Pickup</option>
                                <option value="confirmed">Confirmed</option>
                            </select>
                            <small class="form-text text-danger help-block status_error"></small>
                        </div>
                        <hr>  
                        <div class="delivery-address-container">
                            <h3>Delivery Address</h3>
                            <div class="form-group">
                                <label>Name*:</label>
                                <input type="text" class="form-control" value="" name="delivery_name" placeholder="Delivery name">
                                <small class="form-text text-danger help-block delivery_name_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Email*:</label>
                                <input type="text" class="form-control" value="" name="delivery_email" placeholder="Delivery email">
                                <small class="form-text text-danger help-block delivery_email_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Shipment*:</label>
                                <input type="text" class="form-control" value="" name="delivery_shipment" placeholder="Delivery shipment">
                                <small class="form-text text-danger help-block delivery_shipment_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Line 1*:</label>
                                <input type="text" class="form-control" value="" name="delivery_line_1" placeholder="line 1">
                                <small class="form-text text-danger help-block delivery_line_1_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Country*:</label>
                                <select class="form-control country" name="delivery_country" id="">
                                    <option value="">Select</option>
                                    @if($countries)
                                        @foreach($countries as $co)
                                        <option value="{{ $co->code }}">{{ $co->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="form-text text-danger help-block delivery_country_error"></small>
                            </div>
                            <div class="form-group">
                                <label>City*:</label>
                                <select class="form-control city" name="delivery_city" id="">
                                </select>
                                <small class="form-text text-danger help-block delivery_city_error"></small>
                            </div>
                            <div class="form-group">
                                <label>State*:</label>
                                <select class="form-control state" name="delivery_state" id="">
                                </select>
                                <small class="form-text text-danger help-block delivery_state_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Postal Code*:</label>
                                <input type="text" class="form-control" value="" name="delivery_postal_code" placeholder="Postal code">
                                <small class="form-text text-danger help-block delivery_postal_code_error"></small>
                            </div>
                        </div>  
                        <div class="pickup-address-container display-none">
                            <h3>Pickup Address</h3>
                            <div class="form-group">
                                <label>Name*:</label>
                                <input type="text" class="form-control" value="" name="pickup_name" placeholder="Pickup name">
                                <small class="form-text text-danger help-block pickup_name_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Email*:</label>
                                <input type="text" class="form-control" value="" name="pickup_email" placeholder="Pickup email">
                                <small class="form-text text-danger help-block pickup_email_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Shipment*:</label>
                                <input type="text" class="form-control" value="" name="pickup_shipment" placeholder="Pickup shipment">
                                <small class="form-text text-danger help-block pickup_shipment_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Line 1*:</label>
                                <input type="text" class="form-control" value="" name="pickup_line_1" placeholder="line 1">
                                <small class="form-text text-danger help-block pickup_line_1_error"></small>
                            </div>
                            <div class="form-group">
                            <label>Country*:</label>
                                <select class="form-control country" name="pickup_country" id="">
                                    <option value="">Select</option>
                                    @if($countries)
                                        @foreach($countries as $co)
                                        <option value="{{ $co->code }}">{{ $co->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>City*:</label>
                                <select class="form-control city" name="pickup_city" id="">
                                </select>
                                <small class="form-text text-danger help-block pickup_city_error"></small>
                            </div>
                            <div class="form-group">
                                <label>State*:</label>
                                <select class="form-control state" name="pickup_state" id="">
                                </select>
                                <small class="form-text text-danger help-block pickup_state_error"></small>
                            </div>
                            <div class="form-group">
                                <label>Postal Code*:</label>
                                <input type="text" class="form-control" value="" name="pickup_postal_code" placeholder="Postal code">
                                <small class="form-text text-danger help-block pickup_postal_code_error"></small>
                            </div>
                        </div>    
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h3>Order Items</h3>
                        <form action="" id="order-item-form">
                            <div class="order-item-orig js">
                                <div class="order-item js">
                                    <a href="#" class="order-item-remove js display-none float-right">x</a>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label>Type*:</label>
                                        <input type="text" value="product" name="type[]" class="form-control" readonly>
                                        <small class="form-text text-danger help-block type_error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label>Description*:</label>
                                        <textarea name="description[]" id="" cols="30" rows="2" class="form-control description"></textarea>
                                        <small class="form-text text-danger help-block description_error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label>Amount*:</label>
                                        <input type="text" class="form-control amount" placeholder="Amount" name="amount[]">
                                        <small class="form-text text-danger help-block amount_error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label>Quantity*:</label>
                                        <input type="number" class="form-control quantity" placeholder="quanity" name="quantity[]">
                                        <small class="form-text text-danger help-block quantity_error"></small>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="order-items js"></div>
                            <input type="submit" class="btn btn-primary btn-sm" id="add-item" value="Add item">
                        </form>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection