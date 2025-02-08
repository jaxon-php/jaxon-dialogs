<?php

/**
 * Bootstrap3.php
 *
 * Adapter for the Bootstrap 3 library.
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

class Bootstrap3 extends AbstractLibrary
    implements ModalInterface, AlertInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    const NAME = 'bootstrap3';

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = ['css/bootstrap-dialog.min.css'];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['js/bootstrap-dialog.min.js'];

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
        return 'https://cdn.jsdelivr.net/npm/bootstrap3-dialog@1.35.4/dist';
    }
}
