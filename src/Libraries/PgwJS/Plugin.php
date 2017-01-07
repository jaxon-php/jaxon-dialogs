<?php

namespace Jaxon\Dialogs\Libraries\PgwJS;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Modal
{
    public function getJs()
    {
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/pgwjs/modal/2.0.0/pgwmodal.min.js"></script>';
    }

    public function getCss()
    {
        return '<link href="https://lib.jaxon-php.org/pgwjs/modal/2.0.0/pgwmodal.min.css" rel="stylesheet" type="text/css">';
    }

    public function getScript()
    {
        return '
var pgwModalDefaultOptions = {
    maxWidth: 400
};
var pgwModalUserOptions = {};' . $this->getOptionScript('pgwModalUserOptions.', 'options.modal.') . '
jaxon.command.handler.register("pgwModal", function(args) {
    // Set user and default options into data only when they are missing
    for(key in pgwModalUserOptions)
    {
        if(!(key in args.data))
        {
            args.data[key] = pgwModalUserOptions[key];
        }
    }
    for(key in pgwModalDefaultOptions)
    {
        if(!(key in args.data))
        {
            args.data[key] = pgwModalDefaultOptions[key];
        }
    }
    $.pgwModal(args.data);
});';
    }

    public function show($title, $content, array $buttons, array $options = array())
    {
        // Set the value of the max width, if there is no value defined
        $options['title'] = (string)$title;
        // Buttons
        $modalButtons = '
';
        foreach($buttons as $button)
        {
            if($button['click'] == 'close')
            {
                $modalButtons .= '
            <button type="button" class="' . $button['class'] . '" onclick="$.pgwModal(\'close\')">' . $button['title'] . '</button>';
            }
            else
            {
                $modalButtons .= '
            <button type="button" class="' . $button['class'] . '" onclick="' . $button['click'] . '">' . $button['title'] . '</button>';
            }
        }
        // Dialog body and footer
         $options['content'] = '
        <div class="modal-body">
' . $content . '
        </div>
        <div class="modal-footer" style="padding:10px 5px 5px;">' . $modalButtons . '
        </div>
';
        // Affectations du contenu de la fenêtre
        $this->addCommand(array('cmd'=>'pgwModal'), $options);
    }

    public function hide()
    {
        $this->response()->script('$.pgwModal("close")');
    }
}

?>