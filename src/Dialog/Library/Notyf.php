<?php

/**
 * Notyf.php
 *
 * Adapter for the Toastr library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Dialog\Library;

use Jaxon\Dialogs\Dialog\AbstractLibrary;
use Jaxon\App\Dialog\Library\AlertInterface;

class Notyf extends AbstractLibrary implements AlertInterface
{
    /**
     * @const The library name
     */
    const NAME = 'notyf';

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = ['notyf.min.css'];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['notyf.min.js'];

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return 'https://cdn.jsdelivr.net/npm/notyf@3';
    }
}
