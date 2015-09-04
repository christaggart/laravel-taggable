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

use DraperStudio\ServiceProvider\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    protected $packageName = 'taggable';

    public function register()
    {
        $this->setup(__DIR__)
             ->publishMigrations()
             ->publishConfig()
             ->mergeConfig('taggable');
    }
}
