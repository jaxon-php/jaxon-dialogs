<?php

/**
 * JQueryConfirmLibrary.php
 *
 * Adapter for the JQuery-Confirm library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\JQueryConfirm;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\ModalInterface;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class JQueryConfirmLibrary implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;

    /**
     * @const The library name
     */
    const NAME = 'jconfirm';

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
        return 'jquery-confirm';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '3.3.0';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('jquery-confirm.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('jquery-confirm.min.css') . '
<style>
    .jconfirm .jconfirm-box div.jconfirm-content-pane {
        margin-top: 15px;
    }
</style>
';
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('jqueryconfirm/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('jqueryconfirm/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sMessage, array $aButtons, array $aOptions = [])
    {
        $aOptions['title'] = $sTitle;
        $aOptions['content'] = $sMessage;
        $aOptions['buttons'] = [];
        if(!array_key_exists('boxWidth', $aOptions))
        {
            $aOptions['useBootstrap'] = false;
            $aOptions['boxWidth'] = '600';
        }
        $ind = 0;
        foreach($aButtons as $button)
        {
            $_button = [
                'text' => $button['title'],
                'btnClass' => $button['class'],
                'action' => $button['click'],
            ];
            // Optional attributes
            foreach($button as $attr => $value)
            {
                if(!in_array($attr, ['title', 'class', 'click']))
                {
                    $_button[$attr] = $value;
                }
            }
            $aOptions['buttons']['btn' . $ind++] = $_button;
        }
        // Show dialog
        $this->addCommand('jconfirm.show', $aOptions);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide dialog
        $this->addCommand('jconfirm.hide', []);
    }

    /**
     * @inheritDoc
     */
    protected function alert(string $sMessage, string $sTitle, string $sStdType)
    {
        $aTypes = [
            'success' => 'green',
            'info' => 'blue',
            'warning' => 'orange',
            'error' => 'red',
        ];
        $sIcon = "fa fa-$sStdType";
        $sType = $aTypes[$sStdType] ?? $sStdType;
        $this->addCommand('jconfirm.alert',
            ['content' => $sMessage, 'title' => $sTitle, 'type' => $sType, 'icon' => $sIcon]);
    }
}
