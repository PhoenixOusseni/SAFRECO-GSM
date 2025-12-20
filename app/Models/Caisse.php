<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caisse extends Model
{
    use HasFactory;

    protected $guarded = [

    ];

    protected $casts = [
        'solde' => 'decimal:2',
    ];
}
