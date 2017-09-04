<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class EditorsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

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
				'spintax_copy',
				'spin',
				'protected',
				'synonym',
                'isEditorEdit',
				'isEditorUpdateSC',
                'isCsCheckHitMax',
				'isRespinHitMax',
                'hr_spent_editor_edit_article',
                'min_spent_editor_edit_article',
                'sec_spent_editor_edit_article',
				'words.created_at'
    		]);
    }

    public function updateArticle()
    {
    	$editor_id = request()->user()->id;
    	$article = request('article');
        $time = request('times');
        $times = [$time[0], $time[1], $time[2]];

    	DB::beginTransaction();
		try {
			// update words table set isEdit to true, editor_id to the editor & spin to new article
			$result = Word::where('id', request('id'))->update([
				'spin' => $article,
				'isEditorEdit' => 1,
				'editor_id' => $editor_id,
                'hr_spent_editor_edit_article' => $times[0],
                'min_spent_editor_edit_article' => $times[1],
                'sec_spent_editor_edit_article' => $times[2]
			]);
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

    	return response()->json(['isSuccess' => true, 'result' => $article, 'times' => $times]);
    }

    public function publishArticle() {
		$url = 'https://hooks.zapier.com/hooks/catch/2462016/ryantm/';

		// folder name = domain name
		// file name = title-keyword-domain-com.txt

		$folderName = request('domain');
		$fileName = request('domain') . '-' . request('title') . '-' . request('keyword');
		$fileArticleContent = request('article');
		$fileSpintaxContent = request('spintax');

        return request()->all();

        // update words isPublish column
        // Word::where('id', request('word_id'))->update(['isPublish' => request('time')]);

    	return Curl::to($url)
    		->withData([
    			'folderName' => $folderName,
    			'fileName' => strtolower($fileName),
    			'fileArticleContent' => $fileArticleContent,
    			'fileSpintaxContent' => $fileSpintaxContent
    		])
    		->post();
    }

    public function editorSpentTimeOnEditingArticle() {
        $time = request('times');
        $times = [$time[0], $time[1], $time[2]];

        DB::table('words')->where('id', request('word_id'))->update(['hr_spent_editor_edit_article' => $times[0], 'min_spent_editor_edit_article' => $times[1], 'sec_spent_editor_edit_article' => $times[2]]);

        return $times;
    }
}
