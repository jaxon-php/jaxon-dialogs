<?php

/**
 * NotifyLibrary.php
 *
 * Adapter for the Notify library.
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

class Notify extends AbstractLibrary implements AlertInterface
{
    /**
     * @const The library name
     */
    const NAME = 'notify';

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
        return 'https://cdn.jsdelivr.net/npm/notify-js-legacy@0.4.1';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('notify.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('notify.js');
    }
}
