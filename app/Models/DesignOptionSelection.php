<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignOptionSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'design_id',
        'design_option_id',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /* ========== Relations ========== */

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function option()
    {
        return $this->belongsTo(DesignOption::class, 'design_option_id');
    }
}
