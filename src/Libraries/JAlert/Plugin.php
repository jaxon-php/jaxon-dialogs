<?php

/**
 * Plugin.php - Adapter for the jAlert library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\JAlert;

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
        parent::__construct('jAlert', '4.5.1');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('jAlert.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('jAlert.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('jalert/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('jalert/ready.js.php');
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
    protected function alert($content, $title, $theme)
    {
        if(!$title)
        {
            $title = '&nbsp;';
        }
        if($this->getReturn())
        {
            return "$.jAlert({content:" . $content . ", title:'" . $title . "', theme:'" . $theme . "'})";
        }
        $this->addCommand(array('cmd' => 'jalert.alert'), array('content' => $content, 'title' => $title, 'theme' => $theme));
    }

    /**
     * @inheritDoc
     */
    public function success($message, $title = null)
    {
        return $this->alert($message, $title, 'green');
    }

    /**
     * @inheritDoc
     */
    public function info($message, $title = null)
    {
        return $this->alert($message, $title, 'blue');
    }

    /**
     * @inheritDoc
     */
    public function warning($message, $title = null)
    {
        return $this->alert($message, $title, 'yellow');
    }

    /**
     * @inheritDoc
     */
    public function error($message, $title = null)
    {
        return $this->alert($message, $title, 'red');
    }

    /**
     * @inheritDoc
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getQuestionTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.jalert.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.jalert.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
