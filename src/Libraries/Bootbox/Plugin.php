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
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Plugin extends Library implements Modal, Message, Question
{
    use \Jaxon\Features\Dialogs\Message;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('bootbox', '4.3.0');
    }

    /**
     * The id of the HTML container block
     *
     * @return string
     */
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
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('bootbox.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('bootbox/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('bootbox/ready.js.php', ['container' => $this->getContainer()]);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, $title, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, $title, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, $title, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'danger');
    }

    /**
     * @inheritDoc
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getQuestionTitle();
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
