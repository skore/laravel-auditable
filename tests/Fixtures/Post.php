<?php

namespace SkoreLabs\LaravelAuditable\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkoreLabs\LaravelAuditable\Traits\Auditable;

class Post extends Model
{
    use Auditable;
    use SoftDeletes;

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = ['title', 'content'];
}
