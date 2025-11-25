<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckMovement extends Model
{
    protected $fillable = [
        'terminal_id',
        'container_number',
        'truck_number',
        'customer_id',
        'shipping_line_id',
        'movement_type',
        'container_type',
        'operation_type',
        'driver_name',
        'chassis_number',
        'seal_number',
        'remarks',
        'movement_time',
        'created_by'
    ];

    protected $casts = [
        'movement_time' => 'datetime',
    ];

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_number', 'container_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
