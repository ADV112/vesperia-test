<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Input extends Model
{
    use HasUuids;
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ['answer_key'];

    function options(): HasMany {
        return $this->hasMany(Option::class);
    }
}
