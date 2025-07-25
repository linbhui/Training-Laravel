<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $table= 'employees';

    public $timestamps = false;

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

    protected $casts = [
        'birthday' => 'date',
        'ins_datetime' => 'datetime',
        'upd_datetime' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'ins_id');
    }

    public function updater()
    {
        return $this->belongsTo(Employee::class, 'upd_id');
    }

    public function createdEmployees()
    {
        return $this->hasMany(Employee::class, 'ins_id');
    }

    public function updatedEmployees()
    {
        return $this->hasMany(Employee::class, 'upd_id');
    }
}
