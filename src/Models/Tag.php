<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstud.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Taggable\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Stringy\StaticStringy as S;

/**
 * Class Tag.
 */
class Tag extends Eloquent
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }

    /**
     * Set the name attribute on the model.
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $value = trim($value);
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = S::slugify($value);
    }

    /**
     * Find a tag by its name or create a new one.
     *
     * @param $name
     *
     * @return static
     */
    public static function findOrCreate($name)
    {
        if (!$tag = static::findByName($name)) {
            $tag = static::create(compact('name'));
        }

        return $tag;
    }

    /**
     * Find a tag by its name.
     *
     * @param $name
     *
     * @return mixed
     */
    public static function findByName($name)
    {
        return static::where('slug', S::slugify($name))->first();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getAttribute('name');
    }
}
