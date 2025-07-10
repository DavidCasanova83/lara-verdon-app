<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'slug', 'image'];

    public function getSpecificOptionsAttribute()
    {
        return [
            'annot' => ['Escalade', 'Train à Vapeur', 'Grès d\'Annot'],
            'colmars-les-alpes' => ['Lac d\'Allos', 'Cascade de la Lance', 'Maison Musée'],
            'entrevaux' => ['Nice', 'Cote d\'azur', 'Chemin de ronde', 'Citadelle', 'Gorge de Daluis', 'Train à Vapeur'],
            'la-palud-sur-verdon' => ['Blanc-Martel', 'Route des Crêtes', 'Escalade et via cordatta'],
            'saint-andre-les-alpes' => ['Lac de Castillon', 'Parapente']
        ][$this->slug] ?? [];
    }
}
