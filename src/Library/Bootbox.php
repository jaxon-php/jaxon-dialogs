<?php

/**
 * BootboxLibrary.php
 *
 * Adapter for the Bootbox library.
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

class Bootbox implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'bootbox';

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
        return 'https://cdn.jsdelivr.net/npm/bootbox@6.0.0/dist';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('bootbox.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('bootbox.js');
    }
}
