<?php

/**
 * DialogLibraryInterface.php - Adapter for the PNotify library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\PNotify;

use Jaxon\Dialogs\Libraries\AbstractDialogLibrary;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\QuestionInterface;

class PNotifyLibrary extends AbstractDialogLibrary implements MessageInterface, QuestionInterface
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('pnotify', '3.0.0');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('pnotify.js') . "\n" . $this->getJsCode('pnotify.confirm.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('pnotify.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('pnotify/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('pnotify/ready.js.php', [
            'options' => $this->getOptionScript('PNotify.prototype.options.', 'options.')
        ]);
    }

    /**
     * Print an alert message.
     *
     * @param string $sMessage The text of the message
     * @param string $sTitle The title of the message
     * @param string $sType The type of the message
     *
     * @return string
     */
    protected function alert(string $sMessage, string $sTitle, string $sType): string
    {
        if($this->getReturn())
        {
            return "jaxon.dialogs.pnotify.alert({text:" . $sMessage . ", type:'" . $sType . "', title:'" . $sTitle . "'})";
        }
        $aOptions = array('text' => $sMessage, 'title' => $sTitle, 'type' => $sType);
        // Show the alert
        $this->addCommand(array('cmd' => 'pnotify.alert'), $aOptions);
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
        return $this->alert($sMessage, $sTitle, 'notice');
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
        $sTitle = $this->getQuestionTitle();
        if(!$sNoScript)
        {
            return "jaxon.dialogs.pnotify.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.pnotify.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
        }
    }
}
