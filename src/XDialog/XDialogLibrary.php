<?php

/**
 * XDialogLibrary.php
 *
 * Adapter for the XDialog library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2022 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\XDialog;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\ModalInterface;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class XDialogLibrary implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'xdialog';

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
        return 'https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3';
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('xdialog.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('xdialog.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('xdialog/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('xdialog/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        $aOptions['title'] = $sTitle;
        $aOptions['body'] = $sContent;
        $aOptions['buttons'] = [];
        foreach($aButtons as $aButton)
        {
            if($aButton['click'] === 'close')
            {
                $aOptions['buttons']['cancel'] = $aButton['title'];
                $aOptions['oncancel'] = 'jaxon.dialogs.xdialog.hide()';
            }
            else
            {
                $aOptions['buttons']['ok'] = $aButton['title'];
                $aOptions['onok'] = $aButton['click'];
            }
        }

        // Assign dialog content
        $this->addCommand(['cmd' => 'xdialog.show'], $aOptions);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        $this->addCommand(['cmd' => 'xdialog.hide'], []);
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
        if($this->returnCode())
        {
            return "jaxon.dialogs.xdialog.$sType(" . $sContent . "'" . $sTitle . "')";
        }

        $this->addCommand(['cmd' => "xdialog.$sType"], ['body' => $sContent, 'title' => $sTitle]);
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

        return empty($sNoScript) ?
            "jaxon.dialogs.xdialog.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";})" :
            "jaxon.dialogs.xdialog.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript .
                ";},function(){" . $sNoScript . ";})";
    }
}
