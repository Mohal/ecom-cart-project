<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'is_active'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(CartItem::class); }

    public function subtotal(): float
    {
        return (float) $this->items->sum(fn($i) => $i->quantity * $i->unit_price);
    }
}
