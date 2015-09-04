<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstud.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Taggable\Traits;

use DraperStudio\Taggable\Exceptions\InvalidTagException;
use DraperStudio\Taggable\Models\Tag;
use DraperStudio\Taggable\Util;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Stringy\StaticStringy as S;

/**
 * Class Taggable.
 */
trait Taggable
{
    /**
     * Get a Collection of all Tags a Model has.
     *
     * @return mixed
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Attach one or multiple Tags to a Model.
     *
     * @param $tags
     *
     * @return $this
     */
    public function tag($tags)
    {
        $tags = Util::buildTagArray($tags);

        foreach ($tags as $tag) {
            $this->addOneTag($tag);
        }

        return $this;
    }

    /**
     * Detach one or multiple Tags from a Model.
     *
     * @param $tags
     *
     * @return $this
     */
    public function untag($tags)
    {
        $tags = Util::buildTagArray($tags);

        foreach ($tags as $tag) {
            $this->removeOneTag($tag);
        }

        return $this;
    }

    /**
     * Remove all Tags from a Model and assign the given ones.
     *
     * @param $tags
     *
     * @return $this
     */
    public function retag($tags)
    {
        return $this->detag()->tag($tags);
    }

    /**
     * Remove all Tags from a Model. Alias for removeAllTags.
     *
     * @return $this
     */
    public function detag()
    {
        $this->removeAllTags();

        return $this;
    }

    /**
     * Attach a single Tag to a Model. Creates the Tag if it doesn't exist.
     *
     * @param $string
     */
    protected function addOneTag($string)
    {
        if ($this->onlyUseExistingTags) {
            $tag = Tag::findByName($string);

            if (empty($tag)) {
                throw new InvalidTagException("$string was not found in the list of tags.");
            }
        } else {
            $tag = Tag::findOrCreate($string);
        }

        if (!$this->tags->contains($tag->getKey())) {
            $this->tags()->attach($tag);
        }
    }

    /**
     * Detach a single Tag to a Model.
     *
     * @param $string
     */
    protected function removeOneTag($string)
    {
        if ($tag = Tag::findByName($string)) {
            $this->tags()->detach($tag);
        }
    }

    /**
     * Remove all Tags from a Model.
     */
    protected function removeAllTags()
    {
        $this->tags()->sync([]);
    }

    /**
     * Get all tags of a Model as a string in which the tags are delimited
     * by the character defined in config('taggable.delimiters').
     *
     * @return string
     */
    public function getTagListAttribute()
    {
        return Util::makeTagList($this, 'name');
    }

    /**
     * Get all slug tags of a Model as a string in which the tags are delimited
     * by the character defined in config('taggable.delimiters').
     *
     * @return string
     */
    public function getTagListNormalizedAttribute()
    {
        return Util::makeTagList($this, 'slug');
    }

    /**
     * Get all tags of a Model as an array.
     *
     * @return mixed
     */
    public function getTagArrayAttribute()
    {
        return Util::makeTagArray($this, 'name');
    }

    /**
     * Get all slug tags of a Model as an array.
     *
     * @return mixed
     */
    public function getTagArrayNormalizedAttribute()
    {
        return Util::makeTagArray($this, 'slug');
    }

    /**
     * Scope for a Model that has all of the given Tags.
     *
     * @param $query
     * @param $tags
     *
     * @return mixed
     */
    public function scopeWithAllTags(Builder $query, $tags)
    {
        $tags = Util::buildTagArray($tags);
        $slug = array_map([S::class, 'slugify'], $tags);

        return $query->whereHas('tags', function ($q) use ($slug) {
            $q->whereIn('slug', $slug);
        }, '=', count($slug));
    }

    /**
     * Scope for a Model that has any of the given Tags.
     *
     * @param $query
     * @param array $tags
     *
     * @return mixed
     */
    public function scopeWithAnyTags(Builder $query, $tags = [])
    {
        $tags = Util::buildTagArray($tags);

        if (empty($tags)) {
            return $query->has('tags');
        }

        $slug = array_map([S::class, 'slugify'], $tags);

        return $query->whereHas('tags', function ($q) use ($slug) {
            $q->whereIn('slug', $slug);
        });
    }

    /**
     * Get all tags for the called class.
     *
     * @return mixed
     */
    public static function tagsArray()
    {
        return static::getAllTags(get_called_class());
    }

    /**
     * Get all tags for the called class as a string in which the tags are delimited
     * by the character defined in config('taggable.delimiters').
     *
     * @return string
     */
    public static function tagsList()
    {
        return Util::joinArray(static::getAllTags(get_called_class()));
    }

    /**
     * Get all tags for the given class.
     *
     * @param $className
     *
     * @return mixed
     */
    public static function getAllTags($className)
    {
        return DB::table('taggables')->distinct()
            ->where('taggable_type', '=', $className)
            ->join('tags', 'taggables.tag_id', '=', 'tags.id')
            ->orderBy('tags.slug')
            ->lists('tags.slug');
    }
}
