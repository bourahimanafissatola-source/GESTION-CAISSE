<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieSortie extends Model
{
    protected $table = 'categories_sorties';

    protected $fillable = [
        'libelle',
        'description',
    ];
}