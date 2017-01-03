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
        return '<script type="text/javascript" src="//lib.jaxon-php.org/pgwjs/modal/2.0.0/pgwmodal.min.js"></script>';
    }

    public function getCss()
    {
        return '<link href="//lib.jaxon-php.org/pgwjs/modal/2.0.0/pgwmodal.min.css" rel="stylesheet" type="text/css">';
    }

    public function getScript()
    {
        $sPrefix = 'modal.options.';
        $aOptions = $this->getOptionNames($sPrefix);
        $sScript = '
jaxon.command.handler.register("pgwModal", function(args) {
    var options = {';
        foreach($aOptions as $sname => $name)
        {
            $value = $this->getOption($name);
            if(is_string($value))
            {
                $value = "'$value'";
            }
            else if(is_bool($value))
            {
                $value = ($value ? 'true' : 'false');
            }
            else if(!is_numeric($value))
            {
                $value = print_r($value, true);
            }
            $sScript .= '
        ' . $sname . ': ' . $value . ',';
        }
        return $sScript . '
        title: args.data.title,
        content: args.data.content
    };
    // Override defaults options with call options
    jQuery.extend(options, args.data.options);
    $.pgwModal(options);
});';
    }

    public function show($title, $content, array $buttons, array $options = array())
    {
        // Set the value of the max width, if there is no value defined
        if(!array_key_exists('maxWidth', $options))
        {
            $options['maxWidth'] = 600;
        }
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
        $modalHtml = '
        <div class="modal-body">
' . $content . '
        </div>
        <div class="modal-footer" style="padding:10px 5px 5px;">' . $modalButtons . '
        </div>
';
        // Affectations du contenu de la fenêtre
        $this->addCommand(array('cmd'=>'pgwModal'), array('title' => $title, 'content' => $modalHtml, 'options' => $options));
    }

    public function hide()
    {
        $this->response()->script('$.pgwModal("close")');
    }
}

?>