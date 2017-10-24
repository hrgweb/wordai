<?php

namespace App\Http\Controllers;

use App\Article;
use App\DomainDetail;
use App\Repositories\Copyscape;
use App\Repositories\Spintax;
use App\Repositories\WordRepository;
use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ixudra\Curl\Facades\Curl;

class WordsController extends Controller
{
	private $word;
	private $spin;

	function __construct(WordRepository $word, Spintax $spin)
	{
		$this->middleware('auth');
		$this->word = $word;
		$this->spin = $spin;
	}

	public function test()
	{

	}

	private function generateSpintax(array $request, $articleOrParagraph)
	{
		// dd($request['article']);

		$word = $this->word;

		// api - vars
		$text = $articleOrParagraph;
		$quality = 100;
        $email = 'accounting@connexionsolutions.com';
        $pass = 'privape23';
		$synonyms = $request['synonym'];

		$quality = 'readable';
		$nonested = 'off';
		$sentence = 'on';
		$paragraph = 'on';
		$title = 'off';
		$nooriginal = 'on';

        // default for db values
        $company = '%company%';
        $city = '%city%';
        $state = '%state%';

        $protected = strlen($request['keyword']) > 0 ? $request['keyword'] . ', ' : '';                         // keyword
        $protected .= strlen($request['lsi_terms']) > 0 ? $request['lsi_terms'] . ', ' : '';                    // lsi tems
        $protected .= strlen($request['domain_protected']) > 0 ? $request['domain_protected'] . ', ' : '';      // domain_protected
        $protected .= strlen($request['protected']) > 0 ? $request['protected'] : '';                           // protected
        $protected .= $protected . ',' . $company . ',' . $city . ',' . $state;
        $protected = $this->word->remove_underline_and_spaces_for_terms($protected);
        $synonyms = $request['synonym'];

        // OLD CODE
        // $request['keyword'] = $word->remove_underline_and_spaces_for_terms(request('keyword'));
        // $request['lsi_terms'] = $word->remove_underline_and_spaces_for_terms(request('lsi_terms'));
        // $request['domain_protected'] = $word->remove_underline_and_spaces_for_terms(request('domain_protected'));
        // $request['protected'] = $word->remove_underline_and_spaces_for_terms(request('protected'));

        // $terms_protected =  $request['keyword'] . ',' . $request['lsi_terms'] . ',' . $request['domain_protected'] . ',' . $request['protected'];
        // $terms_protected = $word->remove_underline_and_spaces_for_terms($terms_protected);

		// $paragraphs = $word->split_article_into_paragraph($request->article);

		$result = $this->api(
			$text,
			$quality,
			$email,
			$pass,
			$protected,
			$synonyms,
			$nonested,
			$sentence,
			$paragraph,
			$title,
			$nooriginal
		);

    	return $result;
	}

	public function generateFullArticle()
	{
		$result = $this->spin->process(request('article'));

		return response()->json($result);
	}

	public function generateParagraph()
	{
		$paragraphs = request('paragraphs');
		$result = [];

		for ($i=0; $i < count($paragraphs); $i++) {
			array_push($result, $this->spin->process($paragraphs[$i]));
		}

		return response()->json($result);
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'doc_title' => 'required',
	    	'keyword' => 'required',
	    	'lsi_terms' => 'required',
	    	'domain_protected' => 'required',
	    	'article' => 'required',
	    	'protected' => 'required',
	    	// 'synonyms' => 'required'
        ]);

		// if validation fails
        if ($validator->fails()) {
            return response()->json(['isError' => true, 'errors' => $validator->errors()]);
        }

        // if validation success, generate spintax
        $spintax = $this->generateSpintax($request->all(), $request->article);
        $spintax = json_decode($spintax);

        if (strtolower($spintax->status) === 'success') {
            $request['spintax'] = $spintax->text;  // spintax result
            $request['isProcess'] = 1; // add isProcess to request
            $request['spin'] = $this->spin->process($spintax->text); // spin result

            $result = $this->postSpinTax(request()->all());     // post article

            return response()->json(['isError' => false, 'spintaxStatus' => true, 'result' => $result]);

        } else {
            return response()->json(['isError' => false, 'spintaxStatus' => false, 'result' => $spintax]);
        }

        // return response()->json(['isError' => false, 'result' => $result]);
	}

    public function runWordai(Request $request)
    {
        // if validation success, generate spintax
        $spintax = $this->generateSpintax($request->all(), $request->article);
        $spintax = json_decode($spintax);

        if (strtolower($spintax->status) === 'success') {
            $spin = $this->spin->process($spintax->text); // spin result

            // update article record
            $result = Word::where('id', request('id'))->update([
                'article' => request('article'),
                'spintax' => $spintax->text,
                'spin' => $spin,
                'isProcess' => 1
            ]);

            // update request data
            $request['article'] = $request->article;
            $request['spintax'] = $spintax->text;
            $request['spin'] = $spin;
            $request['isProcess'] = 1;

            if ($result) {
                return response()->json(['isError' => false, 'spintaxStatus' => true, 'result' => $request->all()]);
            }

        } else {
            return response()->json(['isError' => false, 'spintaxStatus' => false, 'result' => $spintax]);
        }
    }

    public function saveAndProcessNow(Request $request) {
        $validator = Validator::make($request->all(), [
            'doc_title' => 'required',
            'keyword' => 'required',
            'lsi_terms' => 'required',
            // 'domain_protected' => 'required',
            'article' => 'required',
            'protected' => 'required',
            // 'synonyms' => 'required'
        ]);

        // if validation fails
        if ($validator->fails()) {
            return response()->json(['isError' => true, 'errors' => $validator->errors()]);
        }

        $spintax = $this->generateSpintax($request->all(), $request->article);
        $spintax = json_decode($spintax);

        if ($spintax->status === 'Success') {
            // if validation success the post article
            $result = $this->postSpinTax();

            return response()->json(['isError' => false, 'result' => $result]);
        } else {
            $spintax = json_encode($spintax);

            return response()->json(['isError' => true, 'spintax' => $spintax]);
        }
    }

	private function generateSpintaxParagraph(array $request, $paragraph)
	{
		return $this->generateSpintax($request, $paragraph);
	}

	public function generateRespintax()
	{
		return $this->generateSpintaxParagraph(request()->all(), request('paragraph'));
	}

	public function postSpinTax($request)
	{
		$result = auth()->user()->words()->create($request);

		return $result;
	}

	private function api($text, $quality, $email, $pass, $protected, $synonyms, $nonested, $sentence, $paragraph, $title, $nooriginal)
	{
		if (isset($text) && isset($quality) && isset($email) && isset($pass))
		{
			$text = urlencode($text);
			$ch = curl_init('http://wordai.com/users/turing-api.php');

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&output=json&protected=$protected&synonyms=$synonyms");

			$result = curl_exec($ch);
			curl_close ($ch);

			return $result;
		}
		else
		{
			return 'Error: Not All Variables Set!';
		}
	}

	public function article()
	{
		return view('words.article');
	}

	public function getRawArticles()
	{
		$authUser = auth()->user();

		$raw = Word::where('user_id', $authUser->id)->get();

		return response()->json($raw);
	}

	public function generateArticle()
	{
		$article = request('spintax');
		$result = $this->spin->process($article);

		auth()->user()->articles()->create(['article' => $result]);

		return response()->json($result);
	}

	public function processCopyscapeApi()
	{
		include_once(app_path() . '/Functions/Copyscape.php');

		$result = copyscape_api_text_search_internet(request('article'), 'UTF-8');

		return response()->json($result);
	}

	public function processToCopyscape()
	{
		include_once(app_path() . '/Functions/Copyscape.php');

		$article = request('article');
		$paragraph = request('paragraph');
		$type = request('type');
		$result = '';
		$articleType = '';

        // return request()->all();

		switch ($type) {
			case 'article':
				$articleType = $article;
				break;
			case 'paragraph':
				$articleType = $paragraph;
				break;
			case 'edit-article':
				$articleType = $article;
				break;
		}

		// retrieve copyscape settings
		$settings = \App\Copyscape::first(['o', 'e', 'c', 'i', 'x']);

		// return $settings;

		$result = copyscape_api_text_search_internet(
			$articleType,
			$settings->e,
			$settings->c,
			$settings->o
		);

        // update csCounter column in words table
        Word::where('id', request('id'))->update(['csCounter' => request('csCounter')]);

		return response()->json($result);
	}

	public function runCurl()
	{
		// With a POST you pass the data via the CURLOPT_POSTFIELDS option instead
		// of passing it in the CURLOPT__URL.
		// -------------------------------------------------------------------------

		// return request('url');

		$curl=curl_init();

		// $qry_str = "x=10&y=20";
		curl_setopt($ch, CURLOPT_URL, 'https://cnn.com');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);

		// Set request method to POST
		curl_setopt($ch, CURLOPT_POST, 1);

		// Set query data here with CURLOPT_POSTFIELDS
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);

		$content = trim(curl_exec($ch));
		curl_close($ch);
		print $content;
	}

	public function processTextGrammar()
	{
		$url = Config::get('textgear.url');
		$key = Config::get('textgear.key');
		$text = request('text');

		$data = [
			'text' => $text,
			'key' => $key
		];

	    $result = Curl::to($url)
	        ->withData($data)
	        ->post();

		return $result;
	}

	public function domainChange()
	{
		return DomainDetail::where('domain_id', request('domain_id'))->first();
	}

	public function unprocessArticles()
	{
		$isUnprocess = Word::where('isProcess', 0)->first();

		return $isUnprocess;
	}

	public function respinArticle() {
        /*=============== Before ===============*/
		/*$vars = request()->only([
			'article',
			'keyword',
			'lsi_terms',
			'domain_protected',
			'protected',
			'synonym',
			'type'
		]);

		$spintax = $this->generateSpintax($vars, request('article'));
		$spintax = json_decode($spintax);

		if ($spintax->status === 'Success') {
			return $this->generateFullArticle();
		} else {
			return json_encode($spintax);
		}*/

        /*=============== After ===============*/

        $spin = $this->spin->process(request('article'));

        // Update words table in spintax_copy
        // $update = Word::where('id', request('word_id'))
                    // ->update(['spin' => $spin]);

        // if ($update) return $spin; // return response

        // update respinCounter column in words table
        Word::where('id', request('word_id'))->update(['respinCounter' => request('respinCounter')]);

        return $spin;
	}

	public function updateSpintaxArticle() {
		$spintax = request('spintax');
        $company = (request('input.company') !== null) ? request('input.company') : '%company%';
        $city = (request('input.city') !== null) ? request('input.city') : '%city%';
        $state = (request('input.state') !== null) ? request('input.state') : '%state%';

        // return request()->all();
        // return $spintax;

        // update words
        // DB::table('words')->where('id', request('word_id'))->update(['spintax_copy' => $spintax, 'isEditorUpdateSC' => 1]);
		Word::where('id', request('id'))->update([
            'spintax_copy' => $spintax,
            'company' => $company,
            'city' => $city,
            'state' => $state,
            'isEditorUpdateSC' => 1
        ]);

        // generate spun article
        $spun = $this->spin->process($spintax);

		return response()->json(['result' => request()->all(), 'spun' => $spun]);
	}

	public function updateCsCheckHitMax() {
		return DB::table('words')->where('id', request('word_id'))->update(['isCsCheckHitMax' => true]);
	}

    public function updateRespinCheckHitMax() {
        return DB::table('words')->where('id', request('word_id'))->update(['isRespinHitMax' => true]);
    }

    public function getKeywordsAssociatedByDomain() {
        return Word::where('domain_id', request('domain_id'))->pluck('keyword');
    }
}
