<?php

/**
 * PluginInterface.php - Adapter for the Bootbox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Bootbox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Ui\Dialogs\ModalInterface;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\LibraryTrait;
use Jaxon\Ui\Dialogs\QuestionInterface;

class Plugin extends Library implements ModalInterface, MessageInterface, QuestionInterface
{
    use LibraryTrait;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('bootbox', '4.3.0');
    }

    /**
     * The id of the HTML container block
     *
     * @return string
     */
    protected function getContainer(): string
    {
        $sContainer = 'bootbox-container';
        if($this->hasOption('dom.container'))
        {
            $sContainer = $this->getOption('dom.container');
        }
        return $sContainer;
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('bootbox.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('bootbox/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('bootbox/ready.js.php', ['container' => $this->getContainer()]);
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // ModalInterface container
        $sContainer = $this->getContainer();

        // Set the value of the max width, if there is no value defined
        $width = array_key_exists('width', $aOptions) ? $aOptions['width'] : 600;
        $html = $this->render('bootbox/dialog.html',
            ['title' => $sTitle, 'content' => $sContent, 'buttons' => $aButtons]);

        // Assign dialog content
        $this->response()->assign($sContainer, 'innerHTML', $html);
        $this->response()->script("$('.modal-dialog').css('width', '{$width}px')");
        $this->response()->script("$('#styledModal').modal('show')");
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        $this->response()->script("$('#styledModal').modal('hide')");
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
        if($this->getReturn())
        {
            return "jaxon.dialogs.bootbox.alert('" . $sType . "'," . $sContent . ",'" . $sTitle . "')";
        }
        $this->addCommand(array('cmd' => 'bootbox'),
            ['type' => $sType, 'content' => $sContent, 'title' => $sTitle]);
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
        return $this->alert($sMessage, $sTitle, 'danger');
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        $sTitle = $this->getQuestionTitle();
        if(!$sNoScript)
        {
            return "jaxon.dialogs.bootbox.confirm(" . $sQuestion . ",'" .
                $sTitle . "',function(){" . $sYesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.bootbox.confirm(" . $sQuestion . ",'" .
                $sTitle . "',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
        }
    }
}
