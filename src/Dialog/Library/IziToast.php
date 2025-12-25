<?php

/**
 * IziToast.php
 *
 * Adapter for the IziToast library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2025 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Dialog\Library;

use Jaxon\Dialogs\Dialog\AbstractLibrary;
use Jaxon\App\Dialog\Library\AlertInterface;
use Jaxon\App\Dialog\Library\ConfirmInterface;

class IziToast extends AbstractLibrary implements AlertInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    public const NAME = 'izitoast';

    /**
     * The css files
     *
     * @var array
     */
    protected $aCssFiles = ['css/iziToast.min.css'];

    /**
     * The js files
     *
     * @var array
     */
    protected $aJsFiles = ['js/iziToast.min.js'];

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
        return 'https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist';
    }
}
