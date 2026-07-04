<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EoiQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'question',
        'description',
        'order_position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EoiAnswer::class)->orderBy('order_position');
    }

    public function userResponses(): HasMany
    {
        return $this->hasMany(EoiUserResponse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_position');
    }
}
