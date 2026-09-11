<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sortie extends Model
{
    protected $fillable = [
        'categorie_sortie_id',
        'libelle',
        'montant',
        'date_sortie',
        'beneficiaire',
        'description',
        'justificatif_path',
        'public_id',
        'user_id',
        'statut',
    ];

    protected $casts = [
        'date_sortie' => 'date',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieSortie::class, 'categorie_sortie_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}