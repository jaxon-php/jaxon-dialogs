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

namespace Jaxon\Dialogs\CuteAlert;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class CuteAlertLibrary implements MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;

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
        return 'https://cdn.jsdelivr.net/gh/gustavosmanc/cute-alert';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('cute-alert.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('style.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('cutealert/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('cutealert/ready.js.php');
    }

    /**
     * Print an alert message.
     *
     * @param string $sContent The text of the message
     * @param string $sTitle The title of the message
     * @param string $sType The type of the message
     *
     * @return string
     */
    protected function alert(string $sContent, string $sTitle, string $sType): string
    {
        if(!$sTitle)
        {
            $sTitle = '&nbsp;';
        }
        if($this->returnCode())
        {
            return "cuteAlert({message:" . $sContent . ", title:'" . $sTitle . "', type:'" . $sType . "'})";
        }

        $this->addCommand(['cmd' => 'cutealert.alert'], ['message' => $sContent, 'title' => $sTitle, 'type' => $sType]);
        return '';
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'error');
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        $sTitle = $this->helper()->getQuestionTitle();

        return "jaxon.dialogs.cutealert.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript .
            ($sNoScript ? ";},function(){" . $sNoScript . ";})" : ";})");
    }
}
