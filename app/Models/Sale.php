<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'sale_date', 'total_amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => number_format($value, 2) . ' BDT',
        );
    }
}
