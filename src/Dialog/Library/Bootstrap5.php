<?php

/**
 * Bootstrap5.php
 *
 * Adapter for the Bootstrap 5 library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Dialog\Library;

use Jaxon\Dialogs\Dialog\AbstractLibrary;
use Jaxon\App\Dialog\Library\ModalInterface;

class Bootstrap5 extends AbstractLibrary implements ModalInterface 
{
    /**
     * @const The library name
     */
    public const NAME = 'bootstrap5';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
