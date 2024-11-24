<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHasSubscribe extends Model
{
    protected $table = 'user_has_subscribe';
    public $timestamps = false;
    protected $primaryKey = 'id_user';
    public $incrementing = false;
}
