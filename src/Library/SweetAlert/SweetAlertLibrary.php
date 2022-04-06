<?php

/**
 * DialogLibraryInterface.php - Adapter for the SweetAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library\SweetAlert;

use Jaxon\Ui\Dialog\Library\AbstractDialogLibrary;
use Jaxon\Ui\Dialog\MessageInterface;
use Jaxon\Ui\Dialog\QuestionInterface;

class SweetAlertLibrary extends AbstractDialogLibrary implements MessageInterface, QuestionInterface
{
    /**
     * @const The library name
     */
    const NAME = 'sweetalert';

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
        return 'sweetalert';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '1.1.1';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->xHelper->getJsCode('sweetalert.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->xHelper->getCssCode('sweetalert.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->xHelper->render('sweetalert/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->xHelper->render('sweetalert/ready.js.php', [
            'options' =>  $this->xHelper->getOptionScript('jaxon.dialogs.swal.options.', 'options.')
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
        if($this->returnCode())
        {
            return "swal({text:" . $sMessage . ", title:'" . $sTitle . "', type:'" . $sType . "'})";
        }
        $aOptions = ['text' => $sMessage, 'title' => '', 'type' => $sType];
        if(($sTitle))
        {
            $aOptions['title'] = $sTitle;
        }
        // Show the alert
        $this->addCommand(['cmd' => 'sweetalert.alert'], $aOptions);
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
        $sTitle = $this->xHelper->getQuestionTitle();
        if(!$sNoScript)
        {
            return "jaxon.dialogs.swal.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.swal.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
        }
    }
}
