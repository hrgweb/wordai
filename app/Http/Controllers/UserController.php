<?php

namespace App\Http\Controllers;

use App\Domain;
use App\DomainDetail;
use App\Group;
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
        return User::all(['id AS user_id', 'firstname', 'lastname']);
    }

    public function allUsers()
    {
        // return User::all();
        return User::where('isDeleted', '0')->get();
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
    	return DB::table('words AS w')
    		->leftJoin('users AS u', 'u.id', '=', 'w.user_id')
    		->leftJoin('article_types AS at', 'at.id', '=', 'w.article_type_id')
    		->leftJoin('domains AS d', 'd.id', '=', 'w.domain_id')
    		->select([
    			'w.id',
    			'u.firstname',
    			'u.lastname',
    			'at.article_type',
    			'd.domain',
    			'doc_title',
    			'keyword',
				'lsi_terms',
				'domain_protected',
				'article',
				'spintax',
				'spin',
				'protected',
				'synonym',
				'w.isUserEdit',
				'w.isEditorEdit',
				'w.editor_id',
                'w.isArticleApprove',
                'w.reasonArticleNotAprrove',
                'w.reasonArticleNotAprroveBody',
				'isProcess',
				'w.created_at'
    		])
            ->where('w.user_id', auth()->user()->id)
            ->latest()
            ->paginate(20);
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
    	return Word::where('id', request('word_id'))->update(['article' => request('article'), 'isUserEdit' => 1]);
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

    public function userDomainList()
    {
        // get all domain_id associated by user id
        $domain_ids = DomainDetail::where('user_id', request('user_id'))->pluck('domain_id');

        // get all domains set only to that user
        $domains = Domain::whereIn('id', $domain_ids)->get();

        return $domains;
    }

    public function groupName()
    {
        return Group::where('id', request('group_id'))->first(['id', 'group']);
    }

    public function removeUser()
    {
        return User::where('id', request('id'))->update(['isDeleted' => 1]);
    }
}
