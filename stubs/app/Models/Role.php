<?php

namespace App\Models;

class Role extends Model
{
    protected $fillable = [
        'name',
        'access',
    ];

    /**
     * The booted method for model
     * 
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('role', function ($query) {
            if (request()->user()->is('root')) return $query;
            else return $query->where('id', '>', 1);
        });
    }

    /**
     * Get abilities for role
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'abilities_roles');
    }

    /**
     * Get users for role
     */
    public function users()
    {
        return $this->hasMany(User::class);
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
        return $query->where('name', 'like', "%$search%");
    }

    /**
     * Check role is an admin role
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        return in_array($this->name, ['Admin', 'Administrator', 'Account Admin']);
    }

    /**
     * Check role is root
     * 
     * @return boolean
     */
    public function isRoot()
    {
        return $this->access === 'root';
    }
}
