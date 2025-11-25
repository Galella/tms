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
        'date_in',
        'dwell_days'
    ];

    protected $dates = [
        'date_in',
        'created_at',
        'updated_at'
    ];

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_number', 'container_number');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function shippingLine()
    {
        return $this->belongsTo(ShippingLine::class, 'shipping_line_id');
    }
}
