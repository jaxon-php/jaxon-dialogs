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
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
    use \Jaxon\Request\Traits\Alert;

    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getJsCode('/jquery-confirm/3.3.0/jquery-confirm.min.js');
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getCss()
    {
        return $this->getCssCode('/jquery-confirm/3.3.0/jquery-confirm.min.css') . '
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
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        return '
jaxon.command.handler.register("jconfirm.show", function(args) {
    // Add buttons
    for(key in args.data.buttons)
    {
        button = args.data.buttons[key];
        if(button.action == "close")
        {
            button.action = function(){jaxon.confirm.jconfirmDialog.close();};
        }
        else
        {
            button.action = new Function(button.action);
        }
    }
    args.data.closeIcon = true;
    if((jaxon.confirm.jconfirmDialog))
    {
        jaxon.confirm.jconfirmDialog.close();
    }
    jaxon.confirm.jconfirmDialog = $.confirm(args.data);
});
jaxon.command.handler.register("jconfirm.hide", function(args) {
    if((jaxon.confirm.jconfirmDialog))
    {
        jaxon.confirm.jconfirmDialog.close();
    }
    jaxon.confirm.jconfirmDialog = null;
});
jaxon.command.handler.register("jconfirm.alert", function(args) {
    $.alert(args.data);
});
jaxon.confirm.jconfirmDialog = null;
jaxon.confirm.jconfirm = function(title, question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    $.confirm({
        title: title,
        content: question,
        buttons: {
            yes: {
                btnClass: "btn-blue",
                text: "' . $this->getYesButtonText() . '",
                action: yesCallback
            },
            no: {
                text: "' . $this->getNoButtonText() . '",
                action: noCallback
            }
        }
    });
};
';
    }

    /**
     * Show a modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
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
            $options['buttons']['btn' . $ind++] = array(
                'text' => $button['title'],
                'btnClass' => $button['class'],
                'action' => $button['click'],
            );
        }
        // Show dialog
        $this->addCommand(array('cmd' => 'jconfirm.show'), $options);
    }

    /**
     * Hide the modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
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
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
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
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
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
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
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
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
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
     * It is a function of the Jaxon\Request\Interfaces\Confirm interface.
     *
     * @return string
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getConfirmTitle();
        if(!$noScript)
        {
            return "jaxon.confirm.jconfirm('" . $title . "'," . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.confirm.jconfirm('" . $title . "'," . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
