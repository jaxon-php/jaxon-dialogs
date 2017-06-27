<?php

/**
 * Plugin.php - Adapter for the Toastr library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Toastr;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert
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
        return $this->getJsCode('/toastr.js/2.1.3/toastr.min.js', 'https://cdnjs.cloudflare.com/ajax/libs');
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
        return $this->getCssCode('/toastr.js/2.1.3/toastr.min.css', 'https://cdnjs.cloudflare.com/ajax/libs');
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
        if($this->getReturn())
        {
            if(($title))
            {
                return "toastr." . $type . "(" . $message . ", '" . $title . "')";
            }
            else
            {
                return "toastr." . $type . "(" . $message . ")";
            }
        }
        $options = array('message' => $message, 'title' => $title);
        // Show the alert
        $this->addCommand(array('cmd' => 'toastr.' . $type), $options);
    }

    /**
     * Print a success message.
     *
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, $title, 'success');
    }
    
    /**
     * Print an information message.
     *
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, $title, 'info');
    }
    
    /**
     * Print a warning message.
     *
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, $title, 'warning');
    }
    
    /**
     * Print an error message.
     *
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'error');
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
