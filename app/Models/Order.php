<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'total_amount',
    ];

        // Relasi dengan model User jika ada
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan transaksi (1 order dapat memiliki banyak transaksi)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
