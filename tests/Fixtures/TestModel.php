<?php

namespace LaraArabDev\TurboTags\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use LaraArabDev\TurboTags\Concerns\HasTags;

class TestModel extends Model
{
    use HasTags;

    protected $guarded = [];

    public $timestamps = false;
}
