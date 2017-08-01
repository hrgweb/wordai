<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
	public function pendingUsers()
    {
    	$users = User::where('status_id', 2)->oldest()->get();

    	return response()->json($users);
    }
    
    public function verifySignup()
    {
    	$verify = DB::table('users')->where('id', request('id'))->update(['status_id' => 1]);

    	return response()->json($verify);
    }

    public function dissmissUser()
    {
    	return User::where('id', request('id'))->delete();
    }

    public function userList()
    {
    	return User::all();
    }
}
