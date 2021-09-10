<?php

namespace Jiannius\Scaffold\Traits;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

trait FileManager
{
    protected $compress = true;

	/**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootFileManager()
    {
        static::deleting(function($file) {
            if ($path = $file->data->path ?? null) {
                if (env('APP_ENV') !== 'production' && Str::startsWith($path, 'prod/')) {
                    abort(500, 'Do not delete production file in ' . env('APP_ENV') . ' environment!');
                }
                else {
                    (self::getStorage())->delete($path);
                }
            }
        });
    }

    /**
     * Scope for is image
     * 
     * @param Builder $query
     * @param boolean $bool
     * @return Builder
     */
    public function scopeIsImage($query, $bool)
    {
        if ($bool === true) return $query->where('mime', 'like', 'image/%');
        if ($bool === false) return $query->where('mime', 'not like', 'image/%');
    }

    /**
     * Get file type attribute
     * 
     * @return string
     */
    public function getTypeAttribute()
    {
        if ($this->mime === 'youtube') return $this->mime;

        $type = (explode('/', $this->mime))[1];

        if ($type === 'ld+json') return 'jsonld';
        if ($type === 'svg+xml') return 'svg';
        if ($type === 'plain') return 'txt';
        if (in_array($type, ['msword', 'vnd.openxmlformats-officedocument.wordprocessingml.document'])) return 'word';
        if (in_array($type, ['vnd.ms-powerpoint', 'vnd.openxmlformats-officedocument.presentationml.presentation'])) return 'ppt';
        if (in_array($type, ['vnd.ms-excel', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) return 'excel';

        return $type;
    }

    /**
     * Upload file to digital ocean
     * and create a file entry in db
     * 
     * @return File
     */
    public static function upload()
    {
        if (request()->hasFile('upload.files')) {
            return self::saveFromUploadedFiles(request()->file('upload.files'));
        }
        else if (request()->hasFile('upload.file')) {
            return self::saveFromUploadedFiles([request()->file('upload.file')]);
        }
        else if (request()->has('upload.urls')) {
            return self::saveFromImageUrls(request()->input('upload.urls'));
        }
        else if (request()->has('upload.youtube')) {
            return self::saveFromYoutube(request()->input('upload.youtube'));
        }
    }

    /**
     * Get the storage instace
     * 
     * @return Storage
     */
    public static function getStorage()
    {
        config([
            'filesystems.disks.do' => [
                'driver' => 's3',
                'key' => env('DO_SPACES_KEY'),
                'secret' => env('DO_SPACES_SECRET'),
                'region' => env('DO_SPACES_REGION'),
                'bucket' => env('DO_SPACES_BUCKET'),
                'endpoint' => env('DO_SPACES_ENDPOINT'),
            ],
        ]);

        return Storage::disk('do');
    }

    /**
     * Save from uploaded files
     * 
     * @param UploadedFile $input
     * @return File
     */
    public static function saveFromUploadedFiles($input)
    {
        $ids = [];
        $storage = self::getStorage();

        foreach ($input as $data) {
            $path = $storage->putFile(env('DO_SPACES_FOLDER'), $data->path(), 'public');
            $file = File::create([
                'name' => $data->getClientOriginalName(),
                'mime' => $data->getClientMimeType(),
                'size' => round($data->getSize()/1024/1024, 5),
                'url' => env('DO_SPACES_CDN') . '/' . $path,
                'data' => [
                    'path' => $path,
                ],
            ]);

            array_push($ids, $file->id);
        }

        if (is_array($input)) return File::whereIn('id', $ids)->get();
        else return File::find($ids[0]);
    }

    /**
     * Save image from web url
     * 
     * @param array $urls
     * @return File
     */
    public static function saveFromImageUrls($urls)
    {
        $ids = [];

        foreach ($urls as $url) {
            $intervention = new ImageManager();
            $image = $intervention->make($url);
            $mime = $image->mime();

            $file = File::create([
                'name' => $url,
                'mime' => $mime,
                'url' => $url,
            ]);

            array_push($ids, $file->id);
        }

        return File::whereIn('id', $ids)->get();
    }

    /**
     * Save from youtube video id
     * 
     * @param array $vids
     * @return File
     */
    public static function saveFromYoutube($vids)
    {
        $ids = [];

        foreach ($vids as $vid) {
            $file = File::create([
                'name' => $vid,
                'mime' => 'youtube',
                'url' => 'https://www.youtube.com/embed/' . $vid,
                'data' => [
                    'vid' => $vid,
                ],
            ]);

            array_push($ids, $file->id);
        }

        return File::whereIn('id', $ids)->get();
    }
}