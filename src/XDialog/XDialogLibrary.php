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
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\ModalInterface;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class XDialogLibrary implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;

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
        $this->addCommand('xdialog.show', $aOptions);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        $this->addCommand('xdialog.hide', []);
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sType)
    {
        $this->addCommand("xdialog.$sType", ['body' => $sMessage, 'title' => $sTitle]);
    }
}
