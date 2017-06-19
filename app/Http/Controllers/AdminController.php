<?php

namespace App\Http\Controllers;

use App\Domain;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	function __construct() {
		$this->middleware('auth');
	}

    public function pendingUsers()
    {
    	$users = User::where('isVerify', 0)->get();

    	return response()->json($users);
    }

    public function postDomain()
    {
    	$result = auth()->user()->domains()->create(request()->all());

    	return response()->json($result);
    }

    public function domainList()
    {
    	$domains = Domain::all();

    	return response()->json($domains);
    }
}
