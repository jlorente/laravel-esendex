<?php

/**
 * Part of the Esendex Laravel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Esendex Laravel
 * @version    1.0.0
 * @author     Jose Lorente
 * @license    BSD License (3-clause)
 * @copyright  (c) 2018, Jose Lorente
 */

namespace Jlorente\Laravel\Esendex\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Esendex Facade.
 *
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 */
class Esendex extends Facade
{

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'esendex';
    }

}
