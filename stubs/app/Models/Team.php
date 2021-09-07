<?php

namespace App\Models;

class Team extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get users for team
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'teams_users');
    }

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")->orWhereHas('users', function($q) use ($search) {
                $q->search($search);
            });
        });
    }

    /**
     * Scope for user id
     * 
     * @param Builder $query
     * @param integer $id
     * @return Builder
     */
    public function scopeUserId($query, $id)
    {
        return $query->whereHas('users', function($q) use ($id) {
            $q->whereIn('users.id', (array)$id);
        });
    }
}
