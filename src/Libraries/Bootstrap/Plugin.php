<?php

namespace Jaxon\Dialogs\Libraries\Bootstrap;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal
{
    public function getScript()
    {
        return '
jaxon.command.handler.register("twbsModal", function(args) {
    if(!$("#" + args.data.container).length)
    {
        $("body").append("<div id=\"" + args.data.container + "\"></div>");
    }
    // jaxon.dom.assign(args.data.container, "innerHTML", args.data.content);
    $("#" + args.data.container).html(args.data.content);
    $(".modal-dialog", args.data.container).css("width", args.data.width + "px");
    $("#draggable").modal("show");
});';
    }

    public function show($title, $content, array $buttons, array $options = array())
    {
        $sContainer = 'modal-container';
        if($this->hasOption('dom.container'))
        {
            $sContainer = $this->getOption('dom.container');
        }

        // Set the value of the max width, if there is no value defined
        $width = array_key_exists('width', $options) ? $options['width'] : 600;

        // Code HTML des boutons
        $modalButtons = '
';
        foreach($buttons as $button)
        {
            if($button['click'] == 'close')
            {
                $modalButtons .= '
                    <button type="button" class="' . $button['class'] .
                    '" data-dismiss="modal">' . $button['title'] . '</button>';
            }
            else
            {
                $modalButtons .= '
                    <button type="button" class="' . $button['class'] . '" onclick="' .
                    $button['click'] . '">' . $button['title'] . '</button>';
            }
        }
        // Code HTML de la fenêtre
        $modalHtml = '
    <!-- /.modal -->
    <div class="modal fade draggable-modal" id="draggable" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">' . $title . '</h4>
                </div>
                <div class="modal-body">
' . $content . '
                </div>
                <div class="modal-footer">' . $modalButtons . '
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
';
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'twbsModal'),
            array('content' => $modalHtml, 'container' => $sContainer, 'width' => $width));
    }

    public function modal($title, $content, array $buttons, array $options = array())
    {
        $this->show($title, $content, $buttons, $options);
    }

    public function hide()
    {
        $this->response()->script('$("#draggable").modal("hide")');
    }
}

?>