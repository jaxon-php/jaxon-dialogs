<?php

/**
 * PluginInterface.php - Adapter for the JQuery-Confirm library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\JQueryConfirm;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\ModalInterface;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\MessageTrait;
use Jaxon\Ui\Dialogs\QuestionInterface;

class Plugin extends Library implements ModalInterface, MessageInterface, QuestionInterface
{
    use MessageTrait;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('jquery-confirm', '3.3.0');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('jquery-confirm.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('jquery-confirm.min.css') . '
<style type="text/css">
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
        return $this->render('jqueryconfirm/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('jqueryconfirm/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    public function show(string $sTitle, string $sMessage, array $aButtons, array $aOptions = [])
    {
        $aOptions['title'] = (string)$sTitle;
        $aOptions['content'] = (string)$sMessage;
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
        $this->addCommand(array('cmd' => 'jconfirm.show'), $aOptions);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide dialog
        $this->addCommand(array('cmd' => 'jconfirm.hide'), array());
    }

    /**
     * Print an alert message.
     *
     * @param string $sMessage The text of the message
     * @param string $sTitle The title of the message
     * @param string $sType The type of the message
     * @param string $sIcon The icon of the message
     *
     * @return string
     */
    protected function alert(string $sMessage, string $sTitle, string $sType, string $sIcon): string
    {
        if($this->getReturn())
        {
            return "$.alert({content:" . $sMessage . ", title:'" . $sTitle .
                "', type:'" . $sType . "', icon:'" . $sIcon . "'})";
        }
        $this->addCommand(array('cmd' => 'jconfirm.alert'),
            ['content' => $sMessage, 'title' => $sTitle, 'type' => $sType, 'icon' => $sIcon]);
        return '';
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'green', 'fa fa-success');
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'blue', 'fa fa-info');
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'orange', 'fa fa-warning');
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'red', 'fa fa-error');
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        $sTitle = $this->getQuestionTitle();
        if(!$sNoScript)
        {
            return "jaxon.dialogs.jconfirm.confirm(" . $sQuestion . ",'" .
                $sTitle . "',function(){" . $sYesScript . ";})";
        }
        return "jaxon.dialogs.jconfirm.confirm(" . $sQuestion . ",'" . $sTitle .
            "',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
    }
}
