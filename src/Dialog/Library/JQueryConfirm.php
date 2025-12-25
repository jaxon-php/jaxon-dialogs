<?php

/**
 * JQueryConfirm.php
 *
 * Adapter for the JQuery-Confirm library.
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

class JQueryConfirm extends AbstractLibrary implements ModalInterface, AlertInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    public const NAME = 'jconfirm';

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = ['jquery-confirm.min.css'];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['jquery-confirm.min.js'];

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
        return 'https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.4/dist';
    }
}
