<?php

/**
 * Plugin.php - Adapter for the Toastr library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Toastr;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert
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
        return '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.js"></script>';
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
        return '<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css">';
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
        return $this->getOptionScript('toastr.options.', 'options.') . '
jaxon.command.handler.register("toastr.info", function(args) {
    if((args.data.title))
        toastr.info(args.data.message, args.data.title);
    else
        toastr.info(args.data.message);
});
jaxon.command.handler.register("toastr.success", function(args) {
    if((args.data.title))
        toastr.success(args.data.message, args.data.title);
    else
        toastr.success(args.data.message);
});
jaxon.command.handler.register("toastr.warning", function(args) {
    if((args.data.title))
        toastr.warning(args.data.message, args.data.title);
    else
        toastr.warning(args.data.message);
});
jaxon.command.handler.register("toastr.error", function(args) {
    if((args.data.title))
        toastr.error(args.data.message, args.data.title);
    else
        toastr.error(args.data.message);
});';
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
    public function success($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.success'), array('message' => $message, 'title' => $title));
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
    public function info($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.info'), array('message' => $message, 'title' => $title));
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
    public function warning($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.warning'), array('message' => $message, 'title' => $title));
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
    public function error($message, $title = '')
    {
        $this->addCommand(array('cmd' => 'toastr.error'), array('message' => $message, 'title' => $title));
    }

    public function remove()
    {
        $this->response()->script('toastr.remove()');
    }

    public function clear()
    {
        $this->response()->script('toastr.clear()');
    }
}

?>