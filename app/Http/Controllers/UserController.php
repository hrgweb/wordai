<?php

namespace App\Http\Controllers;

use App\User;
use App\UserLevel;
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

    public function userLevelList()
    {
    	return UserLevel::all();
    }

    public function updateRole()
    {
    	return DB::table('users')->where('id', request('user_id'))->update(['user_level_id' => request('level')]);
    }

    public function suspendUser()
    {
    	return DB::table('users')->where('id', request('user_id'))->update(['status_id' => 3]);
    }

    public function activateUser()
    {
    	return DB::table('users')->where('id', request('user_id'))->update(['status_id' => 1]);
    }
}
