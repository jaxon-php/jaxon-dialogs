<?php

/**
 * DialogLibraryInterface.php - Adapter for the Bootbox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library\Bootbox;

use Jaxon\Ui\Dialog\Library\AbstractDialogLibrary;
use Jaxon\Ui\Dialog\ModalInterface;
use Jaxon\Ui\Dialog\MessageInterface;
use Jaxon\Ui\Dialog\QuestionInterface;

class BootboxLibrary extends AbstractDialogLibrary implements ModalInterface, MessageInterface, QuestionInterface
{
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
    public function getSubdir(): string
    {
        return 'bootbox';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '4.3.0';
    }

    /**
     * The id of the HTML container block
     *
     * @return string
     */
    protected function getContainer(): string
    {
        $sContainer = 'bootbox-container';
        if($this->xHelper->hasOption('dom.container'))
        {
            $sContainer = $this->xHelper->getOption('dom.container');
        }
        return $sContainer;
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->xHelper->getJsCode('bootbox.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->xHelper->render('bootbox/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->xHelper->render('bootbox/ready.js.php', ['container' => $this->getContainer()]);
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // ModalInterface container
        $sContainer = $this->getContainer();

        // Set the value of the max width, if there is no value defined
        $width = $aOptions['width'] ?? 600;
        $html = $this->xHelper->render('bootbox/dialog.html',
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
        if($this->returnCode())
        {
            return "jaxon.dialogs.bootbox.alert('" . $sType . "'," . $sContent . ",'" . $sTitle . "')";
        }
        $this->addCommand(['cmd' => 'bootbox'], ['type' => $sType, 'content' => $sContent, 'title' => $sTitle]);
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
        $sTitle = $this->xHelper->getQuestionTitle();
        return empty($sNoScript) ?
            "jaxon.dialogs.bootbox.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";})" :
            "jaxon.dialogs.bootbox.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript .
                ";},function(){" . $sNoScript . ";})";
    }
}