<?php

namespace Jaxon\Dialogs\Libraries\Bootbox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
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

    public function getScript()
    {
        // Modal container
        $sContainer = $this->getContainer();

        return '
if(!$("#' . $sContainer . '").length)
{
    $("body").append("<div id=\"' . $sContainer . '\"></div>");
}
jaxon.command.handler.register("bootbox", function(args) {
    bootbox.alert(args.data.content);
});
';
    }

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

    public function modal($title, $content, array $buttons, array $options = array())
    {
        $this->show($title, $content, $buttons, $options);
    }

    public function hide()
    {
        $this->response()->script("$('#styledModal').modal('hide')");
    }

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

    public function success($message, $title = null)
    {
        $this->alert($message, $title, 'success');
    }

    public function info($message, $title = null)
    {
        $this->alert($message, $title, 'info');
    }

    public function warning($message, $title = null)
    {
        $this->alert($message, $title, 'warning');
    }

    public function error($message, $title = null)
    {
        $this->alert($message, $title, 'danger');
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question
     * 
     * This is the implementation of the Jaxon\Request\Interfaces\Confirm interface.
     * 
     * @return string
     */
    public function getScriptWithQuestion($question, $script)
    {
        return "bootbox.confirm('" . addslashes($question) . "',function(res){if(res){" . $script . ";}})";
    }
}
