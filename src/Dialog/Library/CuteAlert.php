<?php

/**
 * CuteAlertLibrary.php
 *
 * Adapter for the CuteAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2022 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Dialog\Library;

use Jaxon\Dialogs\Dialog\AbstractLibrary;
use Jaxon\App\Dialog\Library\AlertInterface;
use Jaxon\App\Dialog\Library\ConfirmInterface;

class CuteAlert extends AbstractLibrary implements AlertInterface, ConfirmInterface
{
    /**
     * @const The library name
     */
    const NAME = 'cute';

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
        return 'https://cdn.jsdelivr.net/gh/jaxon-php/jaxon-js@5.0.0-beta.9/dist/libs/cute-alert';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('cute-alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('style.css');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('cutealert.js');
    }
}
