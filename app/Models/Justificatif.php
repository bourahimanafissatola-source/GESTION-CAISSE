<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Justificatif extends Model
{
    protected $table = 'justificatifs';

    protected $fillable = [
        'entree_id',
        'sortie_id',
        'fichier',
    ];

    public function entree()
    {
        return $this->belongsTo(Entree::class);
    }

    public function sortie()
    {
        return $this->belongsTo(Sortie::class);
    }
}