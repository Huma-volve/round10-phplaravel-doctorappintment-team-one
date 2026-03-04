<?php 

namespace App\Http\Traits;

use Illuminate\Container\Attributes\Storage;

trait HasProfileImage
{
    public function getProfileImageAttribute(): ?string
    {
        $url = $this->photo_url;

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        if (!is_null($url) && is_string($url)) {
            return Storage::disk(config('filesystems.default'))->url($url);
        }

        return null;
    }
}