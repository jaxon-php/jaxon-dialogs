<?php

/**
 * Plugin.php - Adapter for the SweetAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\SweetAlert;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Plugin extends Library implements Message, Question
{
    use \Jaxon\Features\Dialogs\Message;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('sweetalert', '1.1.1');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('sweetalert.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('sweetalert.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('sweetalert/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('sweetalert/ready.js.php', [
            'options' =>  $this->getOptionScript('jaxon.dialogs.swal.options.', 'options.')
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function alert($message, $title, $type)
    {
        if($this->getReturn())
        {
            return "swal({text:" . $message . ", title:'" . $title . "', type:'" . $type . "'})";
        }
        $options = array('text' => $message, 'title' => '', 'type' => $type);
        if(($title))
        {
            $options['title'] = $title;
        }
        // Show the alert
        $this->addCommand(array('cmd' => 'sweetalert.alert'), $options);
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
        return $this->alert($message, $title, 'error');
    }

    /**
     * @inheritDoc
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getQuestionTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.swal.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.swal.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
