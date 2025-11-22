<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;
    protected $table = 'areas';
    protected $fillable = [
        'nombre',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(AreaItem::class, 'areas_id', 'id');
    }

}
