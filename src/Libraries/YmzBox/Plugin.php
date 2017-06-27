<?php

/**
 * Plugin.php - Adapter for the YmzBox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\YmzBox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Plugin extends Library implements Alert, Confirm
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
        return $this->getJsCode('/ymzbox/latest/ymz_box.min.js');
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
        return $this->getCssCode('/ymzbox/latest/ymz_box.css');
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
jaxon.command.handler.register("ymzbox.alert", function(args) {
    ymz.jq_toast(args.data);
});
jaxon.confirm.ymzbox = function(title, question, yesCallback, noCallback){
    if(noCallback == undefined) noCallback = function(){};
    ymz.jq_confirm({
        title: title,
        text: question,
        no_fn: noCallback,
        yes_fn: yesCallback,
        no_btn: "' . $this->getNoButtonText() . '",
        yes_btn: "' . $this->getYesButtonText() . '"
    });
};
';
    }

    /**
     * Print an alert message.
     *
     * @param string              $message              The text of the message
     * @param string              $title                The title of the message
     * @param string              $theme                The type of the message
     *
     * @return void
     */
    protected function alert($text, $title, $type)
    {
        $duration = $this->getOption('options.duration', 3);
        if($this->getReturn())
        {
            return "ymz.jq_toast({text:" . $text . ", type:'" . $type . "', sec:'" . $duration . "'})";
        }
        $this->addCommand(array('cmd' => 'ymzbox.alert'), array('text' => $text, 'type' => $type, 'sec' => $duration));
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
        return $this->alert($message, $title, 'notice');
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
            return "jaxon.confirm.ymzbox('" . $title . "'," . $question . ",function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.confirm.ymzbox('" . $title . "'," . $question . ",function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
