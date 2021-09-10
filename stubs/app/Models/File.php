<?php

namespace App\Models;

use Illuminate\Support\Str;
use Jiannius\Scaffold\Traits\FileManager;

class File extends Model
{
    use FileManager;

    protected $fillable = [
        'name',
        'mime',
        'size',
        'url',
        'data',
    ];

    protected $casts = [
        'size' => 'float',
        'data' => 'object',
    ];

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
            $q
                ->where('name', 'like', "%$search%")
                ->orWhere('url', 'like', "%$search%");
        });
    }
}
