<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOption extends Model
{
    protected $fillable = [
        'item_id',
        'design_option_id',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function option()
    {
        return $this->belongsTo(DesignOption::class, 'design_option_id');
    }
}
