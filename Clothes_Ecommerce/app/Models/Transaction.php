<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public function customer(){
        return $this->belongsTo(Customers::class);
    }


    public function paymentlogs(){
        return $this->hasMany(PaymentLog::class);
    }
}
