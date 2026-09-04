<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieEntree extends Model
{
    protected $table = 'categories_entrees';

    protected $fillable = [
        'libelle',
        'description',
    ];
}