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
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getJsCode('jquery-confirm.min.js');
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
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
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        return $this->render('jqueryconfirm/alert.js');
    }

    /**
     * Show a modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Modal interface.
     *
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     *
     * @return void
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
     * Hide the modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Modal interface.
     *
     * @return void
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
     * Print a success message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $content              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function success($content, $title = null)
    {
        return $this->alert($content, $title, 'green', 'fa fa-success');
    }

    /**
     * Print an information message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $content              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function info($content, $title = null)
    {
        return $this->alert($content, $title, 'blue', 'fa fa-info');
    }

    /**
     * Print a warning message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $content              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function warning($content, $title = null)
    {
        return $this->alert($content, $title, 'orange', 'fa fa-warning');
    }

    /**
     * Print an error message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $content              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function error($content, $title = null)
    {
        return $this->alert($content, $title, 'red', 'fa fa-error');
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Question interface.
     *
     * @return string
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
