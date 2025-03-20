<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'banner',
        'description',
        'date',
        'location',
        'status',
        'max_participants',
        // + tout autre champ nécessaire
    ];

    // Relation avec User (organisateur)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation avec la table pivot (participants)
    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_user')
                    ->withTimestamps();
    }
}
