<?php

/**
 * Plugin.php - Adapter for the SimplyToast library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\SimplyToast;

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
        return '<script type="text/javascript" src="https://lib.jaxon-php.org/simply-toast/latest/simply-toast.min.js"></script>';
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
        return '<link rel="stylesheet" href="https://lib.jaxon-php.org/simply-toast/latest/simply-toast.min.css" />';
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
        $aOptions = $this->getOptionNames('options.');
        return '
jaxon.command.handler.register("simply.alert", function(args) {
    $.simplyToast(args.data.message, args.data.type, ' . json_encode($aOptions) . ');
});';
    }

    /**
     * Print an alert message.
     * 
     * @param string              $message              The text of the message
     * @param string              $type                 The type of the message
     * 
     * @return void
     */
    private function alert($message, $type)
    {
        if($this->getReturn())
        {
            return "$.simplyToast(" . $message . ", '" . $type . "')";
        }
        $this->addCommand(array('cmd' => 'simply.alert'), array('message' => $message, 'type' => $type));
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
        return $this->alert($message, 'success');
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
        return $this->alert($message, 'info');
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
        return $this->alert($message, 'warning');
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
        return $this->alert($message, 'danger');
    }
}
