<?php

/**
 * BootstrapLibrary.php
 *
 * Adapter for the Bootstrap library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library;

use Jaxon\Plugin\Response\Dialog\Library\DialogLibraryTrait;
use Jaxon\Plugin\Response\Dialog\Library\ModalInterface;
use Jaxon\Plugin\Response\Dialog\Library\MessageInterface;
use Jaxon\Plugin\Response\Dialog\Library\QuestionInterface;

class Bootstrap implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'bootstrap';

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

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('js/bootstrap-dialog.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('css/bootstrap-dialog.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('bootstrap.js');
    }
}
