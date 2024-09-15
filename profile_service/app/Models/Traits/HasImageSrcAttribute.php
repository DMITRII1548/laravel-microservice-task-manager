<?php

namespace App\Models\Traits;

trait HasImageSrcAttribute
{
    public function getImageSrcAttribute(): ?string
    {
        return $this->image
            ? url('storage/' . $this->image)
            : null;
    }
}
