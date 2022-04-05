<?php

/**
 * PluginInterface.php - Adapter for the YmzBox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\YmzBox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\QuestionInterface;

class Plugin extends Library implements MessageInterface, QuestionInterface
{
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
    public function getJs(): string
    {
        return $this->getJsCode('ymz_box.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('ymz_box.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('ymzbox/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('ymzbox/ready.js.php', [
            'options' =>  $this->getOptionScript('jaxon.dialogs.ymzbox.', 'options.')
        ]);
    }

    /**
     * Print an alert message.
     *
     * @param string $sMessage The text of the message
     * @param string $sTitle The title of the message
     * @param string $theme The type of the message
     *
     * @return string
     */
    protected function alert(string $text, string $sTitle, string $sType): string
    {
        $duration = $this->getOption('options.duration', 3);
        if($this->getReturn())
        {
            return "ymz.jq_toast({text:" . $text . ", type:'" . $sType . "', sec:'" . $duration . "'})";
        }
        $this->addCommand(array('cmd' => 'ymzbox.alert'), array('text' => $text, 'type' => $sType, 'sec' => $duration));
        return '';
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'notice');
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->alert($sMessage, $sTitle, 'error');
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        $sTitle = $this->getQuestionTitle();
        if(!$sNoScript)
        {
            return "jaxon.dialogs.ymzbox.confirm(" . $sQuestion . ",'" . $sTitle .
                "',function(){" . $sYesScript . ";})";
        }
        return "jaxon.dialogs.ymzbox.confirm(" . $sQuestion . ",'" . $sTitle .
            "',function(){" . $sYesScript . ";},function(){" . $sNoScript . ";})";
    }
}
