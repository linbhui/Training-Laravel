<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table= 'employees';

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

    public function updator()
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
