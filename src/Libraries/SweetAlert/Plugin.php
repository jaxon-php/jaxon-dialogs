<?php

/**
 * Plugin.php - Adapter for the SweetAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\SweetAlert;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
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
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/sweetalert/1.1.1/sweetalert.min.js"></script>';
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
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/sweetalert/latest/sweetalert.css" />';
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
var sweetAlertOptions = {
    allowEscapeKey: true,
    allowOutsideClick: true
};
jaxon.command.handler.register("sweetalert.alert", function(args) {
    // Set user and default options into data only when they are missing
    for(key in sweetAlertOptions)
    {
        if(!(key in args.data))
        {
            args.data[key] = sweetAlertOptions[key];
        }
    }
    swal(args.data);
});
jaxon.confirm.swal = function(question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    swal({type: "warning", title:"", showCancelButton: true, text: question},
        function(isConfirm){
            if(isConfirm)
                yesCallback();
            else
                noCallback();
        }
    );
};
';
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
        $options = array('text' => $message, 'title' => '', 'type' => $type);
        if(($title))
        {
            $options['title'] = $title;
        }
        // Show the alert
        $this->addCommand(array('cmd' => 'sweetalert.alert'), $options);
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
        $this->alert($message, $title, 'error');
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
        if(!$noScript)
        {
            return 'jaxon.confirm.swal(' . $question . ',function(){' . $yesScript . ';})';
        }
        else
        {
            return 'jaxon.confirm.swal(' . $question . ',function(){' . $yesScript . ';},function(){' . $noScript . ';})';
        }
    }
}
