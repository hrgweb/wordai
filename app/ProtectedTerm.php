<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProtectedTerm extends Model
{
	protected $fillable = ['domain_id', 'user_id', 'terms'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
