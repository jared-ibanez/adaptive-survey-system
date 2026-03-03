<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminCred extends Model
{
    protected $table = 'admin_creds';
    protected $primaryKey = 'seq_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
