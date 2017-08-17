<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class EditorsController extends Controller
{
    public function articleList()
    {
    	return DB::table('words')
    		->join('users', 'users.id', '=', 'words.user_id')
    		->join('article_types', 'article_types.id', '=', 'words.article_type_id')
    		->join('domains', 'domains.id', '=', 'words.domain_id')
    		->where('words.isProcess', 1)
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
				'isEditorEdit' => 1,
				'editor_id' => $editor_id
			]);
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

    	return response()->json(['isSuccess' => true, 'result' => $article]);
    }

    public function publishArticle() {
		$url = 'https://hooks.zapier.com/hooks/catch/2462016/ryantm/';
		
		// folder name = domain name
		// file name = title-keyword-domain-com.txt

		$folderName = request('domain');
		$fileName = request('title') . '-' . request('keyword') . '-' . request('domain');
		$fileContent = request('article');

    	return Curl::to($url)
    		->withData([
    			'folderName' => $folderName, 
    			'fileName' => strtolower($fileName), 
    			'fileContent' => $fileContent
    		])
    		->get();
    }
}
