<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    protected $table = 'entrees';
    protected $fillable = [
        'categorie_entree_id',
        'user_id',
        'libelle',
        'montant',
        'date_operation',
        'description',
    ];
    protected $casts = [
        'date_operation' => 'date',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieEntree::class, 'categorie_entree_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}