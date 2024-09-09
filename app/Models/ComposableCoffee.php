<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComposableCoffee extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'ingredients'
    ];
}
