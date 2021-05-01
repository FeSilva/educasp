<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vistoria extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function tipos()
    {
        return $this->hasMany(VistoriaTipo::class);
    }
}
