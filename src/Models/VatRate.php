<?php

namespace Marshmallow\Priceable\Models;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatRate extends Model
{
	use HasSlug, SoftDeletes;

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