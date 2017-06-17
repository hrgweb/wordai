<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;

class WordsController extends Controller
{
	public function store(Request $request)
	{
		// dd($request->all());

		$text = stripslashes($request->words);
		$quality = 100;
		$email = 'accounting@connexionsolutions.com';
		$pass = 'fastredsportscar';
		$protected = $request->protected;
		$synonyms = $request->synonyms;

		$quality = 'very readable';
		$nonested = 'off';
		$sentence = 'on';
		$paragraph = 'on';
		$title = 'off';
		$nooriginal = 'on';

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

	public function postSpinTax()
	{
		// dd(request()->all());

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
		
	}
}
