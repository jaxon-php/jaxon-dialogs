<?php

/**
 * Noty.php
 *
 * Adapter for the Noty library.
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
use Jaxon\App\Dialog\Library\ConfirmInterface;

class Noty extends AbstractLibrary implements AlertInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    public const NAME = 'noty';

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = ['noty.min.css'];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['noty.min.js'];

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
        return 'https://cdn.jsdelivr.net/npm/noty@3.1.4/lib';
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return parent::getCss() . '
<style>
    .noty_buttons button {
        margin-right: 10px;
    }
</style>
';
    }
}
