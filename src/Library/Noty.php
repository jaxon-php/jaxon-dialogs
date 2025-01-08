<?php

/**
 * NotyLibrary.php
 *
 * Adapter for the Noty library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library;

use Jaxon\Plugin\Response\Dialog\Library\DialogLibraryTrait;
use Jaxon\Plugin\Response\Dialog\Library\MessageInterface;
use Jaxon\Plugin\Response\Dialog\Library\QuestionInterface;

class Noty implements MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'noty';

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
    public function getJs(): string
    {
        return $this->helper()->getJsCode('noty.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('noty.min.css') . '
<style>
    .noty_buttons button {
        margin-right: 10px;
    }
</style>
';
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
         return $this->helper()->render('noty.js');
    }
}
