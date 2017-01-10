<?php

namespace App\Models;

class Translation extends MasterModel
{
    public $timestamps = false;

    protected $fillable = ['model_id', 'model_type'];


}
