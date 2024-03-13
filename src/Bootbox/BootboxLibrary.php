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
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\ModalInterface;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class BootboxLibrary implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;

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
        $html = $this->helper()->render('bootbox/dialog.html',
            ['title' => $sTitle, 'content' => $sContent, 'buttons' => $aButtons]);

        // Assign dialog content
        $this->response()->assign($sContainer, 'innerHTML', $html);
        $this->response()->jq('#styledModal')->modal('show');
        if(isset($aOptions['width']))
        {
            // Set the value of the dialog width
            $width = $aOptions['width'];
            $this->response()->jq('.modal-dialog')->css('width', "{$width}px");
        }
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        $this->response()->jq('#styledModal')->modal('hide');
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sStdType)
    {
        $aTypes = [
            'error' => 'danger',
        ];
        $sType = $aTypes[$sStdType] ?? $sStdType;
        $this->addCommand('bootbox', ['type' => $sType, 'content' => $sMessage, 'title' => $sTitle]);
    }
}
