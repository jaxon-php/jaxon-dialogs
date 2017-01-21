<?php

/**
 * Plugin.php - Adapter for the jQuery Modal library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Izi;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Toast extends Library implements Alert, Confirm
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
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/izi-toast/1.1.1/iziToast.min.js"></script>';
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
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/izi-toast/1.1.1/iziToast.min.css" />';
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
jaxon.command.handler.register("izitoast.success", function(args) {
    // Default options
    args.data.position = "topCenter";
    args.data.close = true;
    iziToast.success(args.data);
});
jaxon.command.handler.register("izitoast.info", function(args) {
    // Default options
    args.data.position = "topCenter";
    args.data.close = true;
    iziToast.info(args.data);
});
jaxon.command.handler.register("izitoast.warning", function(args) {
    // Default options
    args.data.position = "topCenter";
    args.data.close = true;
    iziToast.warning(args.data);
});
jaxon.command.handler.register("izitoast.error", function(args) {
    // Default options
    args.data.position = "topCenter";
    args.data.close = true;
    iziToast.error(args.data);
});
jaxon.confirm.izi = function(question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    iziToast.show({
        close: false,
        layout: 2,
        icon: "icon-person",
        position: "center",
        timeout: 0,
        title: "",
        message: question,
        buttons: [
            ["<button>Yes</button>", function (instance, toast) {
                instance.hide({transitionOut: "fadeOutUp"}, toast);
                yesCallback();
            }],
            ["<button>No</button>", function (instance, toast) {
                instance.hide({transitionOut: "fadeOutUp"}, toast);
                noCallback();
            }]
        ],
    });
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
        $options = array('message' => $message);
        // Show the alert
        $this->addCommand(array('cmd' => "izitoast.$type"), $options);
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
            return 'jaxon.confirm.izi(' . $question . ',function(){' . $yesScript . ';})';
        }
        else
        {
            return 'jaxon.confirm.izi(' . $question . ',function(){' . $yesScript . ';},function(){' . $noScript . ';})';
        }
    }
}
