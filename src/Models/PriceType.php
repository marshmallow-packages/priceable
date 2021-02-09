<?php

namespace Marshmallow\Priceable\Models;

use Marshmallow\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceType extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $guarded = [];
}
