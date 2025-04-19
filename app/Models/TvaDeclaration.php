<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvaDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id',
        'type',          // e.g., 'mensuelle', 'trimestrielle', 'annuelle'
        'periode',       // e.g., '2025-Q1', '2025-01', '2025'
        'montant',       // Total TVA amount
        'date_declaration',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}
