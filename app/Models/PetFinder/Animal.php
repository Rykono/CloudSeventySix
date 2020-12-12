<?php

namespace App\Models\PetFinder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
      'breeds' => 'array'
    ];
}
