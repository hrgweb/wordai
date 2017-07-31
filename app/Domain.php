<?php

namespace App;

use App\DomainDetail;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
	protected $fillable = ['domain'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function detail()
	{
		return $this->hasOne(DomainDetail::class);
	}
}
