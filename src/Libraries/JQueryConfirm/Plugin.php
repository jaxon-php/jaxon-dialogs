<?php

/**
 * Plugin.php - Adapter for the JQuery-Confirm library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\JQueryConfirm;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Plugin extends Library implements Modal, Message, Question
{
    use \Jaxon\Features\Dialogs\Message;

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
    public function getJs()
    {
        return $this->getJsCode('jquery-confirm.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
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
    public function getScript()
    {
        return $this->render('jqueryconfirm/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('jqueryconfirm/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        $options['title'] = (string)$title;
        $options['content'] = (string)$content;
        $options['buttons'] = array();
        if(!array_key_exists('boxWidth', $options))
        {
            $options['useBootstrap'] = false;
            $options['boxWidth'] = '600';
        }
        $ind = 0;
        foreach($buttons as $button)
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
            $options['buttons']['btn' . $ind++] = $_button;
        }
        // Show dialog
        $this->addCommand(array('cmd' => 'jconfirm.show'), $options);
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
     * @param string              $content              The text of the message
     * @param string              $title                The title of the message
     * @param string              $class                The type of the message
     *
     * @return void
     */
    protected function alert($content, $title, $type, $icon)
    {
        if($this->getReturn())
        {
            return "$.alert({content:" . $content . ", title:'" . $title . "', type:'" . $type . "', icon:'" . $icon . "'})";
        }
        $this->addCommand(array('cmd' => 'jconfirm.alert'), compact('content', 'title', 'type', 'icon'));
    }

    /**
     * @inheritDoc
     */
    public function success($content, $title = null)
    {
        return $this->alert($content, $title, 'green', 'fa fa-success');
    }

    /**
     * @inheritDoc
     */
    public function info($content, $title = null)
    {
        return $this->alert($content, $title, 'blue', 'fa fa-info');
    }

    /**
     * @inheritDoc
     */
    public function warning($content, $title = null)
    {
        return $this->alert($content, $title, 'orange', 'fa fa-warning');
    }

    /**
     * @inheritDoc
     */
    public function error($content, $title = null)
    {
        return $this->alert($content, $title, 'red', 'fa fa-error');
    }

    /**
     * @inheritDoc
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getQuestionTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.jconfirm.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.jconfirm.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
