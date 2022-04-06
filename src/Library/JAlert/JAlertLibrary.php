<?php

/**
 * DialogLibraryInterface.php - Adapter for the jAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library\JAlert;

use Jaxon\Ui\Dialog\Library\AbstractDialogLibrary;
use Jaxon\Ui\Dialog\MessageInterface;
use Jaxon\Ui\Dialog\QuestionInterface;

class JAlertLibrary extends AbstractDialogLibrary implements MessageInterface, QuestionInterface
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('jAlert', '4.5.1');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->xHelper->getJsCode('jAlert.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->xHelper->getCssCode('jAlert.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->xHelper->render('jalert/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->xHelper->render('jalert/ready.js.php');
    }

    /**
     * Print an alert message.
     *
     * @param string $sContent The text of the message
     * @param string $sTitle The title of the message
     * @param string $sTheme The type of the message
     *
     * @return string
     */
    protected function alert(string $sContent, string $sTitle, string $sTheme): string
    {
        if(!$sTitle)
        {
            $sTitle = '&nbsp;';
        }
        if($this->returnCode())
        {
            return "$.jAlert({content:" . $sContent . ", title:'" . $sTitle . "', theme:'" . $sTheme . "'})";
        }
        $this->addCommand(array('cmd' => 'jalert.alert'), array('content' => $sContent, 'title' => $sTitle, 'theme' => $sTheme));
        return '';
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'green');
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'blue');
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'yellow');
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'red');
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        $sTitle = $this->xHelper->getQuestionTitle();
        if(!$sNoScript)
        {
            return "jaxon.dialogs.jalert.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.jalert.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
        }
    }
}
