<?php

/**
 * Plugin.php - Adapter for the Bootbox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Bootbox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/bootbox/4.3.0/bootbox.min.js"></script>';
    }

    protected function getContainer()
    {
        $sContainer = 'bootbox-container';
        if($this->hasOption('dom.container'))
        {
            $sContainer = $this->getOption('dom.container');
        }
        return $sContainer;
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
        // Modal container
        $sContainer = $this->getContainer();

        return '
if(!$(\'#' . $sContainer . '\').length)
{
    $(\'body\').append(\'<div id="' . $sContainer . '"></div>\');
}
jaxon.command.handler.register("bootbox", function(args) {
    bootbox.alert(args.data.content);
});
jaxon.confirm.bootbox = function(title, question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    bootbox.confirm({
        title: title,
        message: question,
        buttons: {
            cancel: {label: "' . $this->getNoButtonText() . '"},
            confirm: {label: "' . $this->getYesButtonText() . '"}
        },
        callback: function(res){if(res){yesCallback();}else{noCallback();}}
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
        // Modal container
        $sContainer = $this->getContainer();

        // Set the value of the max width, if there is no value defined
        $width = array_key_exists('width', $options) ? $options['width'] : 600;

        // Buttons
        $buttonsHtml = '
';
        foreach($buttons as $button)
        {
            if($button['click'] == 'close')
            {
                $buttonsHtml .= '
            <button type="button" class="' . $button['class'] . '" data-dismiss="modal">' . $button['title'] . '</button>';
            }
            else
            {
                $buttonsHtml .= '
            <button type="button" class="' . $button['class'] . '" onclick="' . $button['click'] . '">' . $button['title'] . '</button>';
            }
        }
        // Dialog
        $dialogHtml = '
    <div id="styledModal" class="modal modal-styled">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">' . $title . '</h3>
                </div>
                <div class="modal-body">
' . $content . '
                </div>
                <div class="modal-footer">' . $buttonsHtml . '
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
';
        // Assign dialog content
        $this->response()->assign($sContainer, 'innerHTML', $dialogHtml);
        $this->response()->script("$('.modal-dialog').css('width', '{$width}px')");
        $this->response()->script("$('#styledModal').modal('show')");
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
        $this->response()->script("$('#styledModal').modal('hide')");
    }

    /**
     * Print an alert message.
     * 
     * @param string              $message              The text of the message
     * @param string              $title                The title of the message
     * @param string              $class                The type of the message
     * 
     * @return void
     */
    protected function alert($message, $title, $class)
    {
        $content = '
        <div class="alert alert-' . $class . '" style="margin-top:15px;margin-bottom:-15px;">
';
        if(($title))
        {
            $content .= '
            <strong>' . $title . '</strong><br/>
';
        }
        $content .= '
            ' . $message . '
        </div>
';
        $this->addCommand(array('cmd' => 'bootbox'), array('content' => $content));
    }

    /**
     * Print a success message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function success($message, $title = null)
    {
        $this->alert($message, $title, 'success');
    }

    /**
     * Print an information message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function info($message, $title = null)
    {
        $this->alert($message, $title, 'info');
    }

    /**
     * Print a warning message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function warning($message, $title = null)
    {
        $this->alert($message, $title, 'warning');
    }

    /**
     * Print an error message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function error($message, $title = null)
    {
        $this->alert($message, $title, 'danger');
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
            return "jaxon.confirm.bootbox('" . $title . "'," . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.confirm.bootbox('" . $title . "'," . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
