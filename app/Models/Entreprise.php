<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Entreprise extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'siege_social',        // No accent, use snake_case
        'form_juridique',
        'activite_principale', // Use snake_case for multi-word attributes
        'numero_societe'
    ];

    public function tvaDeclarations()
{
    return $this->hasMany(TvaDeclaration::class);
}

    // You can add other methods or relationships here if needed
}
