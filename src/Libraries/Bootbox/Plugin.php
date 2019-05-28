<?php

/**
 * Plugin.php - Adapter for the Bootbox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Bootbox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Alert;
use Jaxon\Contracts\Dialogs\Confirm;

class Plugin extends Library implements Modal, Alert, Confirm
{
    use \Jaxon\Features\Dialogs\Alert;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('bootbox', '4.3.0');
    }

    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getJsCode('bootbox.min.js');
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

    /**
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        // Modal container
        $sContainer = $this->getContainer();

        return $this->render('bootbox/alert.js', ['container' => $sContainer]);
    }

    /**
     * Show a modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Modal interface.
     *
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     *
     * @return void
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Modal container
        $sContainer = $this->getContainer();

        // Set the value of the max width, if there is no value defined
        $width = array_key_exists('width', $options) ? $options['width'] : 600;
        $html = $this->render('bootbox/dialog.html', compact('title', 'content', 'buttons'));

        // Assign dialog content
        $this->response()->assign($sContainer, 'innerHTML', $html);
        $this->response()->script("$('.modal-dialog').css('width', '{$width}px')");
        $this->response()->script("$('#styledModal').modal('show')");
    }

    /**
     * Hide the modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Modal interface.
     *
     * @return void
     */
    public function hide()
    {
        $this->response()->script("$('#styledModal').modal('hide')");
    }

    /**
     * Print an alert message.
     *
     * @param string              $content              The text of the message
     * @param string              $title                The title of the message
     * @param string              $type                 The type of the message
     *
     * @return void
     */
    protected function alert($content, $title, $type)
    {
        if($this->getReturn())
        {
            return "jaxon.dialogs.bootbox.alert('" . $type . "'," . $content . ",'" . $title . "')";
        }
        $this->addCommand(array('cmd' => 'bootbox'), compact('type', 'content', 'title'));
    }

    /**
     * Print a success message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
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
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
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
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
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
     * It is a function of the Jaxon\Contracts\Dialogs\Alert interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'danger');
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Confirm interface.
     *
     * @return string
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getConfirmTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.bootbox.confirm(" . $question . ",'" .
                $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.bootbox.confirm(" . $question . ",'" .
                $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
