<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name_ru', 'name_kk', 'logo_path', 'director_full_name', 'updated_by'])]
class OrganizationSettings extends Model
{
    /**
     * Настройки организации существуют в единственном экземпляре.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
