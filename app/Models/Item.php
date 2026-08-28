<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'order_id',
        'design_id',
        'size_id',
        'quantity',
        'unit_price',
        'line_total',
    ];
    protected $casts = [
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function design()
    {
        // Design uses SoftDeletes — a customer deleting their own design
        // must not erase it from other customers' order history. Without
        // withTrashed(), this relationship silently returns null for any
        // order that references a since-deleted design.
        return $this->belongsTo(Design::class)->withTrashed();
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function options()
    {
        return $this->hasMany(ItemOption::class);
    }
}
