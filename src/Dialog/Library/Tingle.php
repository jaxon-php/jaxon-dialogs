<?php

/**
 * Tingle.php
 *
 * Adapter for the Tingle library.
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

class Tingle extends AbstractLibrary implements ModalInterface
{
    /**
     * @const The library name
     */
    public const NAME = 'tingle';

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = ['tingle.min.css'];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['tingle.min.js'];

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
    public function getBaseUrl(): string
    {
        return 'https://cdn.jsdelivr.net/npm/tingle.js@0.16.0/dist';
    }
}
