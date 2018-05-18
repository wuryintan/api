<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;
use JWTAuthException;

class AuthController extends Controller
{
    private $user;
	public function __construct(User $user)
	{
		$this->user = $user;
	}
	
	public function register(Request $request)
	{
		$user = $this->user->create([
			'username' => $request->get('username'),
			'email' => $request->get('email'),
			'password' => bcrypt($request->get('password')),
			'role' => 'user'
		]);
	
		return response()->json(['status'=>true, 'message'=>'User created successfully','data'=>$user]);
	}
	
	public function login(Request $request)
	{
		$credentials = $request->only('username', 'password');
		$token = null;
		
		try{
			if(!$token = JWTAuth::attempt($credentials))
			{
				return response()->json(['invalid_username_or_password'], 422);
			}
			
		}catch (JWTAuthException $e){
			return 'sss';
			return response()->json(['failed_to_create_token'], 500);
		}
		
		//session()->put('getSession', $token);
		return response()->json(compact('token'));
		
	}
	
	public function getToken(Request $request)
	{
		$user = JWTAuth::toUser($request->token);
		//$newToken = JWTAuth::refresh($token);
		//var_dump($user);die;
		return response()->json($user);
	}
	
	
	
	public function isUniqueValue(Request $request)
	{
		$id = $request->get('id');
		$property = $request->get('property');
		$value = $request->get('value');
		
		$user = User::where($property, $value)->get();
		$result = (count($user) == 0);
		
		return response()->json(array(
				'error' => false,
				'isUnique' => $result
			),
			200
		);
		
	}
	
}
