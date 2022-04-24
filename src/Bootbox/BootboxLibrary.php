<?php

/**
 * BootboxLibrary.php
 *
 * Adapter for the Bootbox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Bootbox;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\ModalInterface;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class BootboxLibrary implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;

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
        return $this->helper()->getOption('dom.container', 'bootbox-container');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('bootbox.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('bootbox/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('bootbox/ready.js.php', ['container' => $this->getContainer()]);
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
        $html = $this->helper()->render('bootbox/dialog.html',
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
        $sTitle = $this->helper()->getQuestionTitle();

        return empty($sNoScript) ?
            "jaxon.dialogs.bootbox.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript . ";})" :
            "jaxon.dialogs.bootbox.confirm(" . $sQuestion . ",'" . $sTitle . "',function(){" . $sYesScript .
                ";},function(){" . $sNoScript . ";})";
    }
}
