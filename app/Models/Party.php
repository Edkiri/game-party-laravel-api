<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    protected $fillable = [
        'name',
        'game_id'
    ];
}
