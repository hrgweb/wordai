<?php

namespace App\Http\Controllers;

use App\DomainDetail;
use App\User;
use App\UserLevel;
use App\Word;
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

    public function userArticles()
    {
    	return DB::table('words')
    		->join('users', 'users.id', '=', 'words.user_id')
    		->join('article_types', 'article_types.id', '=', 'words.article_type_id')
    		->join('domains', 'domains.id', '=', 'words.domain_id')
    		->where('words.user_id', auth()->user()->id)
    		->oldest()
    		->get([
    			'words.id',
    			'users.firstname',
    			'users.lastname',
    			'article_types.article_type',
    			'domains.domain',
    			'doc_title',
    			'keyword',
				'lsi_terms',
				'domain_protected',
				'article',
				'spintax',
				'spin',
				'protected',
				'synonym',
				'words.isUserEdit',
				'words.isEditorEdit',
				'words.editor_id',
				'isProcess',
				'words.created_at'
    		]);
    }

    public function editArticle()
    {
    	$word_id = request('wordId');

    	DB::beginTransaction();
		try {
	    	Word::where('id', $word_id)->update(['isUserEdit' => 1]);

	    	// retrieve word obj
	    	$result = Word::findOrFail($word_id);
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

    	return $result;
    }

    public function updateArticle()
    {
    	return Word::where('id', request('word_id'))->update(['article' => request('article'), 'isUserEdit' => 0]);
    }

    public function userDomainSetup() {
    	return DB::table('domain_details as dd')
    		->join('users', 'users.id', '=', 'dd.user_id')
    		->join('domains', 'domains.id', '=', 'dd.domain_id')
    		->where('dd.user_id', auth()->user()->id)
    		->get([
    			'dd.id AS domain_detail_id',
    			'dd.domain_id',
    			'domains.domain',
    			'dd.protected',
    			'dd.synonym'
    		]);
    }

    public function updatePeditor() {
    	$peditor_val = request('peditor') === 'no' ? false : true;

    	DB::table('users')->where('id', request('user_id'))->update(['has_peditor_access' => $peditor_val]);

    	return request('peditor');
    }
}
