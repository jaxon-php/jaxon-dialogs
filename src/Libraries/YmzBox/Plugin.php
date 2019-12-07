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
        parent::__construct('ymzbox', 'latest');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('ymz_box.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('ymz_box.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('ymzbox/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('ymzbox/ready.js.php', [
            'options' =>  $this->getOptionScript('jaxon.dialogs.ymzbox.', 'options.')
        ]);
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
        return $this->alert($message, $title, 'notice');
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
            return "jaxon.dialogs.ymzbox.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.ymzbox.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
