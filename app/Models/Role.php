<?php

namespace App\Models;

class Role extends MasterModel
{

    public $timestamps = false;

    protected $fillable = [
        'alias', 'name', 'description'
    ];

    public $validationRules = [
        '*' => 'required'
    ];

    //Relations

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }
}
