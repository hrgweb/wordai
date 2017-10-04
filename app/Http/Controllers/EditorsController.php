<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;

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
    		// ->oldest()
            ->paginate(10);
    		/*->get([
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
    		]);*/
    }

    protected function columnsNeedForArticle()
    {
        return [
            'words.id AS word_id',
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
            'isProcess',
            'hr_spent_editor_edit_article',
            'min_spent_editor_edit_article',
            'sec_spent_editor_edit_article',
            'words.created_at'
        ];
    }

    public function articlesToEdit() {
        // DB::listen(function($query) { var_dump($query->sql); });

        return DB::table('words')
            ->leftJoin('users', 'users.id', '=', 'words.user_id')
            ->leftJoin('article_types', 'article_types.id', '=', 'words.article_type_id')
            ->leftJoin('domains', 'domains.id', '=', 'words.domain_id')
            ->select($this->columnsNeedForArticle())
            ->where([
                ['isEditorEdit', '=', 0],
                ['isProcess', '=', 1],
                // ['words.hr_spent_editor_edit_article', '<=', 0],
                // ['words.min_spent_editor_edit_article', '<=', 0],
                // ['words.sec_spent_editor_edit_article', '<=', 0]
            ])
            ->orderBy('firstname')
            ->paginate(20);
    }

    public function editedArticles() {
        // DB::listen(function($query) { var_dump($query->sql); });

        return DB::table('words')
            ->leftJoin('users', 'users.id', '=', 'words.user_id')
            ->leftJoin('article_types', 'article_types.id', '=', 'words.article_type_id')
            ->leftJoin('domains', 'domains.id', '=', 'words.domain_id')
            ->select($this->columnsNeedForArticle())
            ->where([
                ['isEditorEdit', '=', 1],
                ['isProcess', '=', 1]
            ])
            // ->where('words.hr_spent_editor_edit_article', '>', 0)
            // ->orWhere('words.min_spent_editor_edit_article', '>', 0)
            // ->orWhere('words.sec_spent_editor_edit_article', '>', 0)
            ->orderBy('firstname')
            ->paginate(20);
    }

    public function articlesToPublish() {
        return DB::table('words')
            ->leftJoin('users', 'users.id', '=', 'words.user_id')
            ->leftJoin('article_types', 'article_types.id', '=', 'words.article_type_id')
            ->leftJoin('domains', 'domains.id', '=', 'words.domain_id')
            ->select($this->columnsNeedForArticle())
            ->where('words.isEditorEdit', 1)
            ->orderBy('firstname')
            ->paginate(20);
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
        /*=============== DROPBOX SDK ===============*/

        /**
         * CREATE 2 FILES ARTICLE & SPINTAX
         */

        $filename = request('domain').'-'.request('title').'-'.request('keyword');
        $domain = request('domain') . '/';
        $folderPath = 'public/published/';
        $path = $folderPath; // . $domain;

        // check if folder not exist then create
        if (! File::exists(storage_path('app/'.$folderPath))) {
            Storage::makeDirectory($folderPath);
        }

        // store the file to disk
        $articlePath = $path . 'article_'.$filename . '.txt';
        $spintaxPath = $path . 'spintax_'.$filename . '.txt';

        try {
            Storage::put($articlePath, request('article'));
            Storage::put($spintaxPath, request('spintax'));
        } catch (Exception $e) {
            // ignore
        }

        $files = ['article_'.$filename, 'spintax_'.$filename];

        /**
         * UPLOAD TO DROPBOX
         */

        define('DBX_CLIENT_ID', 'dmy4zp7f6ebghwx');
        define('DBX_CLIENT_SECRET', 'zmwx6uvuy19y4il');
        define('DBX_ACCESS_TOKEN', 'VpCqPkG8geAAAAAAAAAAka8klHASsdg-TR0iVy5RfcPNLENRn4KhCGkEfYgZ1406');

        $app = new DropboxApp(DBX_CLIENT_ID, DBX_CLIENT_SECRET, DBX_ACCESS_TOKEN);
        $dropbox = new Dropbox($app);

        $articleToUpload = storage_path('app/'.$articlePath);
        $spintaxToUpload = storage_path('app/'.$spintaxPath);

        // File
        $dropboxFile1 = new DropboxFile($articleToUpload);
        $dropboxFile2 = new DropboxFile($spintaxToUpload);

        $article = '/CNXCONTENTMACHINE/ARTICLES/';
        $article = $article.$domain.$filename.'.txt';
        $apintax = '/CNXCONTENTMACHINE/SPINS/';
        $apintax = $apintax.$domain.$filename.'.txt';

        try {
            $file = $dropbox->upload($dropboxFile1, $article, ['mode' => 'overwrite', 'autorename' => false]);
            $file2 = $dropbox->upload($dropboxFile2, $apintax, ['mode' => 'overwrite', 'autorename' => false]);
        } catch (Exception $e) {
            // ignore
        }

        return response()->json(['status' => 'success', 'files' => $files]);




        /*=============== ZAPIER - IMPLEMENTATION ===============*/

		/*$url = 'https://hooks.zapier.com/hooks/catch/2462016/ryantm/';

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
    		->post();*/
    }

    public function removeFiles()
    {
        $files = [request('article'), request('spintax')];
        $path = 'public/published/';

        // delete storage file
        for ($i=0; $i < count($files); $i++) {
            $file = $path.$files[$i].'.txt';
            Storage::delete($file);
        }

        return ['status' => 'success'];
    }

    public function editorSpentTimeOnEditingArticle() {
        $time = request('times');
        $times = [$time[0], $time[1], $time[2]];

        DB::table('words')
            ->where('id', request('word_id'))
            ->update([
                'hr_spent_editor_edit_article' => $times[0],
                'min_spent_editor_edit_article' => $times[1],
                'sec_spent_editor_edit_article' => $times[2]
            ]);

        return $times;
    }
}
