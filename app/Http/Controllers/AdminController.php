<?php

namespace App\Http\Controllers;

use App\Domain;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function updateDomain()
    {
    	$domain = request('domain');
    	$result = DB::table('domains')->where('id', request('id'))->update(['domain' => $domain]);

    	return response()->json(['result' => $result, 'domain' => $domain]);
    }

    public function removeDomain()
    {
    	$id = request('id');
    	$result = DB::table('domains')->where('id', $id)->delete();

    	return response()->json($result);
    }

    public function postProtectedTerms()
    {
    	$terms = explode(',', request('terms'));
    	$terms = implode(',', request('terms'));

    	return $terms;

    	$result = auth()->user()->terms()->create(request()->all());

    	return response()->json($result);
    	
    }

    public function dissmissUser()
    {
    	return User::where('id', request('id'))->delete();
    }

    public function saveDetails()
    {
    	return request()->all();
    }
}
