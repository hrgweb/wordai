<?php 

namespace App\Repositories;

class WordRepository
{
	public function remove_underline_and_spaces_for_terms($terms)
	{
		return preg_replace ('/,(\s+)/', ',', $terms);
	}
}

?>