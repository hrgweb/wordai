<?php 

namespace App\Repositories;

class WordRepository
{
	public function remove_underline_and_spaces_for_terms($terms)
	{
		return preg_replace ('/,(\s+)/', ',', $terms);
	}

	public function split_article_into_paragraph($article)
	{
		return preg_split("/\\n\\n\\n/", $article);
	}
}

?>