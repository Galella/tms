<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Terminal extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
