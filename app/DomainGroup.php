<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DomainGroup extends Model
{
    protected $fillable = ['group_id', 'user_id'];
    protected $table = 'domain_group';

    public function users() {
        return $this->hasMany(User::class);
    }
}
