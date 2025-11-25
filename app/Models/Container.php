<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    protected $casts = [
        'size' => 'string',
    ];

    /**
     * Terminals that this container is associated with
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function terminals(): BelongsToMany
    {
        return $this->belongsToMany(Terminal::class, 'terminal_container', 'container_number', 'terminal_id');
    }

    /**
     * Active inventory records for this container
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeInventories()
    {
        return $this->hasMany(ActiveInventory::class, 'container_number', 'container_number');
    }
}
