<?php

/**
 * BootstrapLibrary.php
 *
 * Adapter for the Bootstrap library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Bootstrap;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\Library\MessageTrait;
use Jaxon\App\Dialog\ModalInterface;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class BootstrapLibrary implements ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;
    use MessageTrait;

    /**
     * @const The library name
     */
    const NAME = 'bootstrap';

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
        return 'bootstrap-dialog';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '1.35.3';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('bootstrap-dialog.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('bootstrap-dialog.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('bootstrap/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('bootstrap/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // Fill the options array with the parameters
        $aOptions['title'] = $sTitle;
        $aOptions['message'] = $sContent;
        $aOptions['buttons'] = [];
        foreach($aButtons as $button)
        {
            $_button = [
                'label' => $button['title'],
                'cssClass' => $button['class'],
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
            $aOptions['buttons'][] = $_button;
        }
        // Turn the value of the nl2br option to false, because it alters form rendering.
        if(!array_key_exists('nl2br', $aOptions))
        {
            $aOptions['nl2br'] = false;
        }
        // Show the modal dialog
        $this->addCommand('bootstrap.show', $aOptions);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand('bootstrap.hide', []);
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
        $aOptions = ['message' => $sMessage, 'type' => $sType];
        if(($sTitle))
        {
            $aOptions['title'] = $sTitle;
        }
        $this->addCommand('bootstrap.alert', $aOptions);
    }
}
