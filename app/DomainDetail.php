<?php

namespace App;

use App\Domain;
use Illuminate\Database\Eloquent\Model;

class DomainDetail extends Model
{
    protected $fillable = ['user_id', 'domain_id', 'group_id', 'protected', 'synonym'];

    public function setProtectedAttribute($protected)
    {
    	$this->attributes['protected'] = strtolower($protected);
    }

    public function domain()
    {
    	return $this->belongsTo(Domain::class);
    }
}
