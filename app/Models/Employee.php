<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table= 'employee';

    public $timestamps = true;
    const CREATED_AT = 'ins_datetime';
    const UPDATED_AT = 'upd_datetime';


    protected $fillable = [
        'team_id',
        'email',
        'first_name',
        'last_name',
        'password',
        'gender',
        'birthday',
        'address',
        'avatar',
        'salary',
        'position',
        'status',
        'type_of_work',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag',
    ];

    protected $hidden = [
        'password'
    ];
}
