<?php

/**
 * PluginInterface.php - Adapter for the SweetAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\SweetAlert;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\LibraryTrait;
use Jaxon\Ui\Dialogs\QuestionInterface;

class Plugin extends Library implements MessageInterface, QuestionInterface
{
    use LibraryTrait;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('sweetalert', '1.1.1');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('sweetalert.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('sweetalert.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('sweetalert/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('sweetalert/ready.js.php', [
            'options' =>  $this->getOptionScript('jaxon.dialogs.swal.options.', 'options.')
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sType)
    {
        if($this->getReturn())
        {
            return "swal({text:" . $sMessage . ", title:'" . $sTitle . "', type:'" . $sType . "'})";
        }
        $aOptions = array('text' => $sMessage, 'title' => '', 'type' => $sType);
        if(($sTitle))
        {
            $aOptions['title'] = $sTitle;
        }
        // Show the alert
        $this->addCommand(array('cmd' => 'sweetalert.alert'), $aOptions);
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
        $sTitle = $this->getQuestionTitle();
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
