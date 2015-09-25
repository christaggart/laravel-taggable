<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstud.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Taggable;

use DraperStudio\Taggable\Contracts\Taggable;

/**
 * Class Util.
 */
class Util
{
    /**
     * Build an array of Tags from a string in which the tags are delimited
     * by the character defined in config('taggable.delimiters').
     *
     * @param $tags
     *
     * @return array
     */
    public static function buildTagArray($tags)
    {
        if (is_array($tags)) {
            return $tags;
        }

        if (is_string($tags)) {
            $tags = preg_split('#['.preg_quote(
                config('taggable.delimiters', ','), '#'
            ).']#', $tags, null, PREG_SPLIT_NO_EMPTY);
        }

        return $tags;
    }

    /**
     * Build an array which contains all Tags of the given model.
     *
     * @param Taggable $model
     * @param $field
     *
     * @return mixed
     */
    public static function makeTagArray(Taggable $model, $field)
    {
        return $model->tags()->lists($field, 'tag_id');
    }

    /**
     * Build a string in which the Tags are delimited by the character
     * defined in config('taggable.delimiters').
     *
     * @param Taggable $model
     * @param $field
     *
     * @return string
     */
    public static function makeTagList(Taggable $model, $field)
    {
        return static::joinArray(
            static::makeTagArray($model, $field)->toArray()
        );
    }

    /**
     * Join the given tags into a string in which the tags are delimited
     * by the character defined in config('taggable.delimiters').
     *
     * @param array $pieces
     *
     * @return string
     */
    public static function joinArray(array $pieces)
    {
        return implode(
            substr(config('taggable.delimiters', ','), 0, 1), $pieces
        );
    }
}
