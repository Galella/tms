<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveInventory extends Model
{
    protected $table = 'active_inventory';

    protected $fillable = [
        'terminal_id',
        'container_number',
        'customer_id',
        'shipping_line_id',
        'status',
        'block',
        'row',
        'tier',
        'date_in'
    ];

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_number', 'container_number');
    }
}
