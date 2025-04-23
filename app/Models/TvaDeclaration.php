<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvaDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id',
        'type',        
        'periode',       
        'montant',       
        'date_declaration',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}
