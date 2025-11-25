<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = [
        'container_number',
        'size',
        'type',
        'ownership',
        'iso_code'
    ];

    protected $primaryKey = 'container_number';

    public $incrementing = false;

    protected $keyType = 'string';
}
