<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsdExchange extends Model
{
    use HasFactory;

    protected $fillable = ['buy', 'sell'];
}
