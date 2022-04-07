<?php

/**
 * DialogLibraryInterface.php - Adapter for the Noty library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library\Noty;

use Jaxon\Ui\Dialog\Library\DialogLibraryTrait;
use Jaxon\Ui\Dialog\LibraryInterface;
use Jaxon\Ui\Dialog\MessageInterface;
use Jaxon\Ui\Dialog\QuestionInterface;

class NotyLibrary implements LibraryInterface, MessageInterface, QuestionInterface
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
    public function getSubdir(): string
    {
        return 'noty';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '2.3.11';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('jquery.noty.packaged.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
         return $this->helper()->render('noty/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
         return $this->helper()->render('noty/ready.js.php');
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
        if($this->returnCode())
        {
            return "noty({text:" . $sMessage . ", type:'" . $sType . "', layout: 'topCenter'})";
        }
        $aOptions = array('text' => $sMessage, 'type' => $sType);
        // Show the alert
        $this->addCommand(array('cmd' => 'noty.alert'), $aOptions);
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
        return $this->alert($sMessage, $sTitle, 'information');
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
        if(!$sNoScript)
        {
            return "jaxon.dialogs.noty.confirm(" . $sQuestion . ",'',function(){" . $sYesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.noty.confirm(" . $sQuestion . ",'',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
        }
    }
}
