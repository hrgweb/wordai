<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;

class WordsController extends Controller
{
	public function store(Request $request)
	{
		$text = stripslashes($request->words);
		$quality = 100;
		$email = 'accounting@connexionsolutions.com';
		$pass = 'fastredsportscar';

		$result = $this->api($text, $quality, $email, $pass);

		return $result;
	}

	public function postSpinTax()
	{
		Word::create(['spintax' => request('words')]);
	}

	private function api($text, $quality, $email, $pass)
	{
		if (isset($text) && isset($quality) && isset($email) && isset($pass))
		{
			$text = urlencode($text);
			$ch = curl_init('http://wordai.com/users/regular-api.php');
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&output=json");
			
			$result = curl_exec($ch);
			curl_close ($ch);

			return $result;
		}
		else
		{
			return 'Error: Not All Variables Set!';
		}
	}
}
