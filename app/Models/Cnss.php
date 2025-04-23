<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cnss extends Model
{
    use HasFactory;

    protected $table = 'cnss';

    protected $fillable = [
        'entreprise_id',
        'Mois',      
        'annee',      
        'Nbr_Salries',
        'etat'
    ];

    protected $appends = ['french_month'];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function getFrenchMonthAttribute()
    {
        $months = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        
        return $months[$this->Mois] ?? 'Mois invalide';
    }
}
