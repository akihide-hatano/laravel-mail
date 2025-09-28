<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /** @use HasFactory<\Database\Factories\CardFactory> */
    use HasFactory;

    protected $fillable =[
        'title',
        'description',
        'is_archived'];
    protected $casts =[
        'is_archived'=>'boolean'
    ];
}
