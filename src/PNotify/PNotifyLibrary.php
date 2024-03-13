<?php

/**
 * PNotifyLibrary.php
 *
 * Adapter for the PNotify library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\PNotify;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\Library\QuestionTrait;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class PNotifyLibrary implements MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;
    use QuestionTrait;

    /**
     * @const The library name
     */
    const NAME = 'pnotify';

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
    public function getSubdir(): string
    {
        return 'pnotify';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '3.0.0';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('pnotify.js') . "\n" . $this->helper()->getJsCode('pnotify.confirm.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('pnotify.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('pnotify/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('pnotify/ready.js.php', [
            'options' => $this->helper()->getOptionScript('PNotify.prototype.options.', 'options.')
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sStdType)
    {
        $aTypes = [
            'warning' => 'notice',
        ];
        $sType = $aTypes[$sStdType] ?? $sStdType;
        $this->addCommand('pnotify.alert', ['text' => $sMessage, 'title' => $sTitle, 'type' => $sType]);
    }
}
