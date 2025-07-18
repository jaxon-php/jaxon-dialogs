<?php

/**
 * Alertify.php
 *
 * Adapter for the Alertify library.
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
use Jaxon\App\Dialog\Library\ModalInterface;

class Alertify extends AbstractLibrary implements ModalInterface, AlertInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    public const NAME = 'alertify';

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = ['css/alertify.min.css', 'css/themes/default.min.css'];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['alertify.min.js'];

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
        return 'https://cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build';
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return parent::getCss() . '
<style>
    .ajs-footer .ajs-buttons .btn {
        margin-right: 10px;
    }
</style>
';
    }
}
