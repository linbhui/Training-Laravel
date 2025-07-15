<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'team';

    public $timestamps = true;
    const CREATED_AT = 'ins_datetime';
    const UPDATED_AT = 'upd_datetime';

    protected $fillable = [
        'name',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag',
    ];


}
