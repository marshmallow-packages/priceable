<?php

namespace Marshmallow\Priceable\Models;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class VatRate extends Model
{
	use HasSlug;

	protected $guarded = [];

    public function multiplier ()
    {
        return 1 + $this->rate / 100;
    }

	/**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}