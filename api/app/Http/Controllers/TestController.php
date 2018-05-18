<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function getSession()
	{
		return response()->json(session()->get('token'));
	}
}
