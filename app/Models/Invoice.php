<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'amount',
        'status',
        'date_payed',
        'amount_paid',
        'balance',
        'user_id',
        'customer_id',
        "credit_adjustment",
        "debit_adjustment",
        "is_reviewed",
        'remarks',
        'current_amount_collected'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
