<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AuthController extends Controller
{
   	public $successStatus = 200;
  
	public function register(Request $request) {    
		$validator = Validator::make($request->all(), [ 
	      	'password' => 'required',  
			'c_password' => 'required|same:password',
			'mobile' => 'required|integer|unique:users'   
	     ]);
		//fail is 1
		if ($validator->fails()) {          
		    return response()->json([
		    	'success'		=> 1,
		    	'status_code'	=> 401, 
		    	'error'			=>	$validator->errors(),
			], 401);                        
		}
		//success is 0
		$input = $request->all();  
		
		$input['email'] = ($request->get('email')) 
		? $request->get('email')
		: $request->get('mobile').'@gmail.com';
		
		$input['password'] = bcrypt($input['password']);
		$input['name'] = ($request->get('name')) 
		? $request->get('name') 
		: $request->get('mobile');
		
		$user = User::create($input); 
		return response()->json([
			'success'		=>	0,
			'status_code'	=> $this->successStatus,
			'data'			=> $user
		], $this->successStatus); 
	}
	  
	//success is 0 
	//fail is 1
	public function login(){
		if($this->checkUser(request('mobile'))){
			if(Auth::attempt(['mobile' => request('mobile'), 'password' => request('password')])){ 
		   		$user = Auth::user(); 
		    	return response()->json([
		    		'success' 		=> 0,
		    		'status_code' 	=> $this->successStatus,
		    		'data' 			=> $user
		    	], $this->successStatus); 
			} else{ 
		   		return response()->json([
		   			'success' 		=> 1,
		   			'status_code'	=> 401,
		   			'error'			=>'Unauthorised'
		   		], 401); 
			}
		}else{
			return response()->json([
				'success' 		=> 1,
		   		'status_code'	=> 400,
				'error'			=>'User not found'
			], 400);
		}  
	}
	
	public function checkUser($mobile)
	{
		$user = User::where('mobile', $mobile)->first();
		if($user){
			return true;
		}
		return false;
	}

	public function getUser() {
	 	$user = Auth::user();
	 	if($user){
	 		return response()->json(['success' => $user], $this->successStatus);	
	 	}
	 	return response()->json(['error'=>'user logged out'], 401); 
	}
}
