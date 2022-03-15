<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Countries;
use App\Models\Plan;
use App\Models\UserCards;
use App\Models\UserPlanSubscriptions;
use App\Models\JobPayments;
use App\Traits\Slug;

use Carbon\Carbon;
use Validator;
use Session;
use Stripe;
use Image;
use File;
use Auth;
use DB;

class HomeController extends Controller{

	use Slug;

	public function card(){
		try {
			$data['page_title'] 	= 'Card';
			$data['user_cards'] 	= UserCards::where('user_id', Auth::id())->where('is_active', 1)->orderBy('is_default', 'DESC')->get();
			return view('front.card',$data);
		} catch (\Exception $e) {
		    return abort(404);
		}
	}

	public function card_verify(Request $request){
		$rules 		= [
            'card_holder'   => 'required|string|max:255',
            'card_number'   => 'required',
            'expire_date'   => 'required|string|size:7|date_format:m/Y',
            'cvv'           => 'required|numeric|digits:3',
        ];
        $validator  = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['errors'] 	= $validator->errors()->all();
			$response['success']    = false;
			$response['message']    = "Oops! Something went wrong..";
			return response()->json($response);
        } else {
            try {
            	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            	$stripe 		= new \Stripe\StripeClient(env('STRIPE_SECRET'));
            	$user 			= Auth::user();

                $card_number 	= str_replace(' ', '', $request->card_number);
                $card   		= UserCards::where('card_number',$card_number)->where('user_id',Auth::id())->count();
                if($card > 0){
                    $response['success'] 	= false;
				    $response['message'] 	= "Card already added.";
				    return response()->json($response);
                }

                $expiry    		= explode('/', $request->expire_date);
                $card_token 	= $stripe->tokens->create([
                    'card' 	=> [
                        'number'    => $card_number,
                        'exp_month' => $expiry[0],
                        'exp_year'  => $expiry[1],
                        'cvc'       => $request->cvv,
                    ]
                ]);
                $card_payment_method = $stripe->paymentMethods->create([
                	'type' 	=> 'card',
				  	'card' 	=> [
                        'number'    => $card_number,
                        'exp_month' => $expiry[0],
                        'exp_year'  => $expiry[1],
                        'cvc'       => $request->cvv,
                    ]
				]);
		        if (is_null($user->stripe_customer_id)) {
					$create_customer = $stripe->customers->create([
					  	'name' 				=> $user->name,
					  	'email' 			=> $user->email,
					  	'phone' 			=> ($user->phone) ? $user->phone : '',
					  	'description' 		=> 'This is created at subscription',
					  	'payment_method' 	=> $card_payment_method->id,
					]);
					$user->stripe_customer_id = $create_customer->id;
					$user->save();
				}
				$stripe->paymentMethods->attach(
					$card_payment_method->id,
					['customer' => $user->stripe_customer_id]
				);
				/* Assign Card To Customer 24-02-2021 */
				$updateCustomer = $stripe->customers->update(
				  $user->stripe_customer_id,
				  ['source'=>$card_token->id]
				);

                $card_expire 					= $expiry[0]."/".$expiry[1];
                $card   						= new UserCards();
                $card->user_id          		= Auth::id();
                $card->card_id          		= $card_token->card->id;
                $card->stripe_card_token_id 	= $card_token->id;
                $card->stripe_payment_method_id = $card_payment_method->id;
                $card->card_brand       		= $card_token->card->brand;
                $card->card_holder      		= $request->card_holder;
                $card->card_number      		= $card_number;
                $card->card_expire      		= $card_expire;
                $card->last_4_digit     		= $newstring = substr($card_number, -4);
                $card->cvv              		= $request->cvv;
                $card->is_active        		= 1;
                if($request->card_id){
                    $card->updated_at   		= date("Y-m-d H:i:s");
                }else{
                	$check_another_card 		= UserCards::where('user_id', Auth::id())->count();
                	if($check_another_card == 0){
                	    $card->is_default 		= 1;
                	}
                    $card->created_at   		= date("Y-m-d H:i:s");
                }
                if($request->has('is_default') && $request->is_default=='1'){
                    $another_cards 				= UserCards::where('user_id', Auth::id())->update(['is_default'=>0]);
                    $card->is_default 			= 1;
                }
                if($card->save()){
                	$response['success'] 		= true;
				    $response['message'] 		= "Card added successfully.";
                }else{
                	$response['success'] 		= false;
				    $response['message'] 		= "Card could not be added.";
                }
            } catch (\Stripe\Exception\CardException $e) {
                $body   = $e->getJsonBody();
                $response['success'] 	= false;
				$response['message'] 	= $body['error']['message'];
            }
            return response()->json($response);
        }
	}

	public function make_default(Request $request){
	    if ($request->ajax()) {
	        try {
	        	$usercard   = UserCards::where('user_id', Auth::id())->where('id', $request->id)->first();
	            if(!is_null($usercard)){
	            	$cards  = UserCards::where('user_id', Auth::id())->update(['is_default'=>0]);
	                $usercard->is_default         	= 1;
	                $usercard->updated_at        	= date("Y-m-d H:i:s");
	                if($usercard->save()){
	                    $response['success']        = true;
	                    $response['message']        = "Card updated successfully.";
	                }else{
	                    $response['success']        = false;
	                    $response['message']        = "Card updated unsuccessfully.";
	                }
	            }else{
	                $response['success']            = false;
	                $response['message']            = "Oops! Something went wrong..";
	            }
	        } catch (\Exception $e) {
	            $response['success']                = false;
	            $response['message']                = $e->getMessage();
	        }
	        return response()->json($response);
	    }else{
	        return abort(404);
	    }
	}

	public function delete_card(Request $request){
		if($request->ajax()){
			$card   = UserCards::where('id',$request->card_id)->where('user_id',Auth::id())->count();
			if($card > 0){
				UserCards::where('id',$request->card_id)->where('user_id',Auth::id())->delete();
				return response()->json(['success'=>true,'message'=>'Your card has been removed.']);
			}
			return response()->json(['success'=>false,'message'=>'Card not found.']);
		}
		return abort(404);
	}

	public function stripeConnect(Request $request){
		$response = ['success'=>false, 'message'=>'Oops! Something went wrong..'];
		if ($request->ajax()) {
			try {
		        $rules      	= [
		            'account_holder_type'   => 'required',
		            'routing_number'        => 'required',
		            'account_number'        => 'required',
		        ];
		        $messages   	= [
		            'account_holder_type'   => 'The account holder field is required.',
		            'routing_number'        => 'The routing number field is required.',
		            'account_number'        => 'The account number field is required.',
		        ];
		        $validator 		= Validator::make($request->all(), $rules, $messages);
		         if ($validator->fails()) {
	                $response['errors'] 	= $validator->errors()->all();
					$response['success']    = false;
					$response['message']    = "Oops! Something went wrong..";
					return response()->json($response);
	            } else {
	            	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
	            	$stripe 	= new \Stripe\StripeClient(env('STRIPE_SECRET'));

	            	$user 		= Auth::user();
	            	if($user->country_id==38){
		            	if($user->stripe_account_id == ''){

		            	    $dob    = explode('-', $user->dob);
		            	    $day   	= $dob[2];
		            	    $month  = $dob[1];
		            	    $year   = $dob[0];

		            	    $account_links  = $stripe->accounts->create([
		            	        'type'          => 'custom',
		            	        'country'       => 'CA',
		            	        'email'         => $user->email,
		            	        'capabilities'  => [
		            	            'card_payments' => ['requested' => true],
		            	            'transfers'     => ['requested' => true],
		            	        ],
		            	    ]);

		            	    $account        = \Stripe\Account::update(
		            	        $account_links->id,
		            	        [
		            	            'tos_acceptance'=> [
		            	                'date' 	=> time(),
		            	                'ip'    => $request->ip(),
		            	            ],
		            	        ]
		            	    );

		            	    $updated       	= $stripe->accounts->update(
		            	        $account->id,
		            	        [
		            	            'business_type' => 'individual',
		            	            'individual'    => [
		            	                'first_name'=> $user->first_name,
		            	                'last_name' => $user->last_name,
		            	                'dob'       => [
		            	                    'day'   		=> $day,
		            	                    'month' 		=> $month,
		            	                    'year'  		=> $year,
		            	                ],
		            	                'address'   => [
		            	                    'line1'         => $user->address,
		            	                    'line2'         => $user->address_line_2,
		            	                    'postal_code'   => $user->pin_code,
		            	                    'city'          => $user->city->name,
		            	                    'state'         => $user->state->name,
		            	                    'country'       => 'CA',
		            	                ]
		            	            ],
		            	            'tos_acceptance'=> [
		            	                'date'              => time(),
		            	                'ip'                => $request->ip(),
		            	            ]
		            	        ]
		            	    );

		            	    $user->stripe_account_id = $account_links->id;
		            	    $user->save();
		            	}

		            	$create_bank_account 		= $stripe->accounts->createExternalAccount(
		            	    $user->stripe_account_id,
		            	    [
		            	        'external_account' 	=> [
		            	            'object'                => 'bank_account',
		            	            'default_for_currency'  => true,
		            	            'country'               => 'CA',
		            	            'currency'              => 'CAD',
		            	            'account_holder_name'   => $user->first_name.' '.$user->last_name,
		            	            'account_holder_type'   => $request->account_holder_type,
		            	            'routing_number'        => $request->routing_number,
		            	            'account_number'        => $request->account_number,
		            	        ]
		            	    ]
		            	);

		            	$bank_arr           		= ($user->stripe_bank_account && $user->stripe_bank_account != '') ? json_decode($user->stripe_bank_account,true) : [];
		            	$user_bank_arr      		= array(array(
		            	    'account_holder_type'   => $request->account_holder_type,
		            	    'routing_number'        => $request->routing_number,
		            	    'account_number'        => $request->account_number,
		            	    'bank_account_id'       => $create_bank_account->id
		            	));
		            	$all_bank_acc_arr   		= array_merge($bank_arr,$user_bank_arr);

		            	$user->stripe_bank_account  = json_encode(array_values($all_bank_acc_arr));
		            	$user->save();

	    	        	$response['success'] 		= true;
	    				$response['message'] 		= 'Account added successfully.';
	            	}
	            }
	        } catch (\Exception $e) {
	        	$response['success'] = false;
				$response['message'] = $e->getMessage();
	        }
	    }
        return response()->json($response);
	}

	public function makePayment(Request $request){
	    try {
	        $user 		= Auth::user();
	        $jobs 		= Jobs::where('is_active', 1)->where('id', $request->job_id)->first();
	        $user_card 	= UserCards::where('id',$request->user_card_id)->first();
	        $job_id 	= $request->job_id;
	        $user_id 	= $user->id;
	        $to_user_id = $request->to_user_id;
	        $JobBids 	= JobBids::where('is_accepted',1)->where('bid_status',1)->where('is_active',1)->where('job_id',$job_id)->where('bid_by',$to_user_id)->first();
	        if($JobBids){
	        	$to_account 	= User::find($to_user_id);
		        if($to_account && $to_account->stripe_account_id != ""){
		        	$job_amount 		= $JobBids->bid_price;
		        	$job_amount_pais 	= $job_amount * 100;
		        	$application_fee_amount 	= 1 * 100;
		        	\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		            $stripe 	= new \Stripe\StripeClient(env('STRIPE_SECRET'));
					$charge 	= \Stripe\PaymentIntent::create([
						'customer'	=> $user->stripe_customer_id,
						'source' 	=> $user_card->card_id,
						'confirm'	=> true,
						'amount' 	=> $job_amount_pais,
						'currency' 	=> 'CURRENCY_CODE',
						'application_fee_amount' => $application_fee_amount, // Amount credited to main stripe account
						'transfer_data' 	=> [
							'destination' 	=> $to_account->stripe_account_id, // Amount credited to connected account
						],
					]);
					$txn_id 				= $charge->id;
					$pay_response 			= json_encode($charge->toArray());

					$objPymt 				= new \App\Models\JobPayments();
					$objPymt->user_id 		= $user_id;
					$objPymt->to_user_id 	= $to_user_id;
					$objPymt->job_id 		= $job_id;
					$objPymt->job_amount 	= $job_amount;
					//$objPymt->commission 	= $commission;
					//$objPymt->net_amount 	= ($job_amount - $commission);
					$objPymt->net_amount 	= $job_amount;
					$objPymt->pay_type 		= 1;
					$objPymt->card_id 		= $user_card->id;
					$objPymt->txn_id 		= $txn_id;
					$objPymt->pay_response 	= $pay_response;
					$objPymt->is_active 	= 1;
					if($objPymt->save()){
						$jobs->job_payment  = 1;
						$jobs->updated_by   = Auth::id();
						$jobs->updated_at   = date("Y-m-d H:i:s");
						$jobs->save();
					}
					return response()->json(['success'=>true,'message'=>'Amount paid successfully.','payment_id'=>base64_encode($objPymt->id)]);
		        } else {
		        	return response()->json(['success'=>false,'message'=> $to_account->first_name.' '.$to_account->last_name.' has not any bank account added, kinldy ask to add bank account first before pay.']);
		        	return response()->json(['success'=>false,'message'=>'User Has No Account, Not Allowed To Pay']);
		        }
	        } else {
	        	return response()->json(['success'=>false,'message'=>'No Job Bid Found For Payment']);
	        }
	    } catch (\Exception $ex) {
	        return response()->json(['success'=>false,'message'=>$ex->getMessage()]);
	    }
	}
	
}
