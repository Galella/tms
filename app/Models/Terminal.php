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

    public function containers()
    {
        return $this->belongsToMany(Container::class, 'terminal_container', 'terminal_id', 'container_number');
    }
}
