<?php

namespace App\Http\Controllers;

use App\Domain;
use App\DomainDetail;
use App\User;
use App\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
	function __construct() {
		$this->middleware('auth');
	}

    public function postDomain()
    {
    	// validate domain to avoid duplicate entry
		$validator = Validator::make(request()->all(), [
			'domain' => 'required|unique:domains'
		]);

		if ($validator->fails()) {
			return response()->json(['isSuccess' => false, 'result' => $validator->errors()]);
		}

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
    	// validate domain to avoid duplicate entry
		$validator = Validator::make(request()->all(), [
			'domain' => 'required|unique:domains'
		]);

		if ($validator->fails()) {
			return response()->json(['isSuccess' => false, 'result' => $validator->errors()]);
		}

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

    public function saveDetails()
    {
    	return DomainDetail::create(request()->all());
    }

    public function domainDetails()
    {
    	return \App\DomainDetail::with('domain')->get();
    }

    public function updateDetails()
    {
    	// return request()->all();

    	return DomainDetail::where('id', request('id'))->update(request()->except(['id', 'domain', 'created_at']));
    }

    public function removeDetails()
    {
    	return DomainDetail::where('id', request('id'))->delete();
    }
}
