<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    protected $fillable = [
        'name',
        'game_id'
    ];
}
