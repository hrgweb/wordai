<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditorsController extends Controller
{
    public function articleList()
    {
    	return DB::table('words')
    		->join('users', 'users.id', '=', 'words.user_id')
    		->join('article_types', 'article_types.id', '=', 'words.article_type_id')
    		->join('domains', 'domains.id', '=', 'words.domain_id')
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
				'words.created_at'
    		]);
    }

    public function updateArticle()
    {
    	$editor_id = request()->user()->id;
    	$article = request('article');

    	DB::beginTransaction();
		try {
			// update words table set isEdit to true, editor_id to the editor & spin to new article
			$result = Word::where('id', request('id'))->update([
				'spin' => $article,
				'isEdit' => true,
				'editor_id' => $editor_id
			]);
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

    	return response()->json(['isSuccess' => true, 'result' => $article]);
    }
}
