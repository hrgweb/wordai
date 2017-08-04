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
		dd(env('COPYSCAPE_API_KEY'));
		return \Config::get('copyscape.username'); 

		dd(env('COPYSCAPE_USERNAME'));
	}

	private function generateSpintax(array $request, $articleOrParagraph)
	{
		// dd($request['article']);

		$word = $this->word;

		$request['keyword'] = $word->remove_underline_and_spaces_for_terms(request('keyword'));
		$request['lsi_terms'] = $word->remove_underline_and_spaces_for_terms(request('lsi_terms'));
		$request['domain_protected'] = $word->remove_underline_and_spaces_for_terms(request('domain_protected'));
		$request['protected'] = $word->remove_underline_and_spaces_for_terms(request('protected'));

		$terms_protected =  $request['keyword'] . ',' . $request['lsi_terms'] . ',' . $request['domain_protected'] . ',' . $request['protected'];
		$terms_protected = $word->remove_underline_and_spaces_for_terms($terms_protected);

		// api - vars
		// $text = stripslashes($request['article']);
		$quality = 100;
		$email = 'accounting@connexionsolutions.com';
		$pass = 'fastredsportscar';
		$protected = $request['protected'];
		$synonyms = $request['synonym'];

		$quality = 'readable';
		$nonested = 'off';
		$sentence = 'on';
		$paragraph = 'on';
		$title = 'off';
		$nooriginal = 'on';

		// $paragraphs = $word->split_article_into_paragraph($request->article);

		$result = $this->api(
			stripslashes($articleOrParagraph),
			$quality, 
			$email, 
			$pass, 
			$terms_protected, 
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
		/*$validator = Validator::make($request->all(), [
            'doc_title' => 'required', 
	    	'keyword' => 'required',
	    	'lsi_terms' => 'required',
	    	// 'domain_protected' => 'required',
	    	'article' => 'required',
	    	'dom_name' => 'required', 
	    	'protected' => 'required',
	    	// 'synonyms' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['isError' => true, 'errors' => $validator->errors()]);
        }*/

        return $this->generateSpintax($request->all(), $request->article);
	}

	private function generateSpintaxParagraph(array $request, $paragraph)
	{
		return $this->generateSpintax($request, $paragraph);
	}

	public function generateRespintax()
	{
		return $this->generateSpintaxParagraph(request()->all(), request('paragraph'));
	}

	public function postSpinTax()
	{
		dd(request()->all());

		$result = auth()->user()->words()->create(request()->all());

		return response()->json($result);
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

		switch ($type) {
			case 'article':
				$articleType = $article;
				break;
			case 'paragraph':
				$articleType = $paragraph;
				break;
		}

		$result = copyscape_api_text_search_internet($articleType, 'UTF-8');

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
}
