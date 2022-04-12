<?php

/**
 * DialogLibraryInterface.php - Adapter for the Lobibox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Library\Lobibox;

use Jaxon\App\Dialog\Library\DialogLibraryTrait;
use Jaxon\App\Dialog\LibraryInterface;
use Jaxon\App\Dialog\ModalInterface;
use Jaxon\App\Dialog\MessageInterface;
use Jaxon\App\Dialog\QuestionInterface;

class LobiboxLibrary implements LibraryInterface, ModalInterface, MessageInterface, QuestionInterface
{
    use DialogLibraryTrait;

    /**
     * @const The library name
     */
    const NAME = 'lobibox';

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
        return 'lobibox';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '1.2.4';
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->helper()->getJsCode('lobibox.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->helper()->getCssCode('lobibox.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->helper()->render('lobibox/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->helper()->render('lobibox/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sContent, array $aButtons, array $aOptions = [])
    {
        // Fill the options array with the parameters
        $aOptions['title'] = $sTitle;
        $aOptions['content'] = $sContent;
        $aOptions['buttons'] = [];
        $ind = 0;
        foreach($aButtons as $button)
        {
            $_button = [
                'text' => $button['title'],
                'action' => $button['click'],
                'class' => $button['class'],
            ];
            // Optional attributes
            foreach($button as $attr => $value)
            {
                if(!in_array($attr, ['title', 'class', 'click']))
                {
                    $_button[$attr] = $value;
                }
            }
            $aOptions['buttons']['btn' . $ind] = $_button;
            $ind++;
        }
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'lobibox.show'), $aOptions);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'lobibox.hide'), array());
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
    protected function notify(string $sMessage, string $sTitle, string $sType): string
    {
        if($this->returnCode())
        {
            return "Lobibox.notify('" . $sType . "', {title:'" . $sTitle . "', msg:" . $sMessage . "})";
        }
        $aOptions = array('message' => $sMessage, 'type' => $sType, 'title' => (($sTitle) ?: false));
        // Show the alert
        $this->addCommand(array('cmd' => 'lobibox.notify'), $aOptions);
        return '';
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->notify($sMessage, $sTitle, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->notify($sMessage, $sTitle, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->notify($sMessage, $sTitle, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->notify($sMessage, $sTitle, 'error');
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        $sTitle = $this->helper()->getQuestionTitle();
        if(!$sNoScript)
        {
            return "jaxon.dialogs.lobibox.confirm(" . $sQuestion . ",'" .
                $sTitle . "',function(){" . $sYesScript . ";})";
        }
        return "jaxon.dialogs.lobibox.confirm(" . $sQuestion . ",'" .
            $sTitle . "',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
    }
}
