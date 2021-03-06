<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstud.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Taggable\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Taggable.
 */
interface Taggable
{
    /**
     * Get a Collection of all Tags a Model has.
     *
     * @return mixed
     */
    public function tags();

    /**
     * Attach one or multiple Tags to a Model.
     *
     * @param $tags
     *
     * @return $this
     */
    public function tag($tags);

    /**
     * Detach one or multiple Tags from a Model.
     *
     * @param $tags
     *
     * @return $this
     */
    public function untag($tags);

    /**
     * Remove all Tags from a Model and assign the given ones.
     *
     * @param $tags
     *
     * @return $this
     */
    public function retag($tags);

    /**
     * Remove all Tags from a Model. Alias for removeAllTags.
     *
     * @return $this
     */
    public function detag();

    /**
     * Scope for a Model that has all of the given Tags.
     *
     * @param $query
     * @param $tags
     *
     * @return mixed
     */
    public function scopeWithAllTags(Builder $query, $tags);

    /**
     * Scope for a Model that has any of the given Tags.
     *
     * @param $query
     * @param array $tags
     *
     * @return mixed
     */
    public function scopeWithAnyTags(Builder $query, $tags = array());

    /**
     * Get all tags for the called class.
     *
     * @return mixed
     */
    public static function tagsArray();

    /**
     * Get all tags for the called class as a string in which the tags are delimited
     * by the character defined in config('taggable.delimiters').
     *
     * @return string
     */
    public static function tagsList();
}
