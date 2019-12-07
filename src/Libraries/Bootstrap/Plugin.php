<?php

/**
 * Plugin.php - Adapter for the Bootstrap library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Bootstrap;

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
        parent::__construct('bootstrap-dialog', '1.35.3');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('bootstrap-dialog.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('bootstrap-dialog.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('bootstrap/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('bootstrap/ready.js.php');
    }

    /**
     * @inheritDoc
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Fill the options array with the parameters
        $options['title'] = (string)$title;
        $options['message'] = (string)$content;
        $options['buttons'] = array();
        foreach($buttons as $button)
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
            $options['buttons'][] = $_button;
        }
        // Turn the value of the nl2br option to false, because it alters form rendering.
        if(!array_key_exists('nl2br', $options))
        {
            $options['nl2br'] = false;
        }
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'bootstrap.show'), $options);
    }

    /**
     * @inheritDoc
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'bootstrap.hide'), array());
    }

    /**
     * Print an alert message.
     *
     * @param string              $message              The text of the message
     * @param string              $title                The title of the message
     * @param string              $type                 The type of the message
     *
     * @return void
     */
    protected function alert($message, $title, $type)
    {
        if($this->getReturn())
        {
            $aDataTypes = [
                'success' => 'BootstrapDialog.TYPE_SUCCESS',
                'info' => 'BootstrapDialog.TYPE_INFO',
                'warning' => 'BootstrapDialog.TYPE_WARNING',
                'danger' => 'BootstrapDialog.TYPE_DANGER',
            ];
            $type = $aDataTypes[$type];
            if(($title))
            {
                return "BootstrapDialog.alert({message:" . $message . ", title:'" . $title . "', type:" . $type . "})";
            }
            else
            {
                return "BootstrapDialog.alert({message:" . $message . ", type:" . $type . "})";
            }
        }
        $options = array('message' => $message, 'type' => $type);
        if(($title))
        {
            $options['title'] = $title;
        }
        // Show the alert
        $this->addCommand(array('cmd' => 'bootstrap.alert'), $options);
    }

    /**
     * @inheritDoc
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, $title, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, $title, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, $title, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'danger');
    }

    /**
     * @inheritDoc
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getQuestionTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.bootstrap.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.bootstrap.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
