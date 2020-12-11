<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestForPayment extends Model
{
    use HasFactory;

    protected $table = 'accounting.request_for_payment';
}
