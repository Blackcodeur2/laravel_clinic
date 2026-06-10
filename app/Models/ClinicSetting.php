<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'nom_clinique',
    'slogan',
    'adresse',
    'ville',
    'telephone',
    'email',
    'site_web',
    'logo_path',
])]
class ClinicSetting extends Model
{
    /**
     * Retrieve the single clinic settings row, creating defaults if needed.
     */
    public static function getInstance(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['nom_clinique' => 'MyClinic']
        );
    }
}
