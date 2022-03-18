<?php

/**
 * PluginInterface.php - Adapter for the Toastr library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Toastr;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\MessageTrait;

class Plugin extends Library implements MessageInterface
{
    use MessageTrait;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('toastr.js', '2.1.3');
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return $this->getJsCode('toastr.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return $this->getCssCode('toastr.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return $this->render('toastr/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return $this->render('toastr/ready.js.php', [
            'options' =>  $this->getOptionScript('toastr.options.', 'options.')
        ]);
    }

    /**
     * Print an alert message.
     *
     * @param string $sMessage The text of the message
     * @param string $sTitle The title of the message
     * @param string $sType The type of the message
     *
     * @return string
     */
    protected function alert(string $sMessage, string $sTitle, string $sType): string
    {
        if($this->getReturn())
        {
            if(($sTitle))
            {
                return "toastr." . $sType . "(" . $sMessage . ", '" . $sTitle . "')";
            }
            else
            {
                return "toastr." . $sType . "(" . $sMessage . ")";
            }
        }
        $aOptions = array('message' => $sMessage, 'title' => $sTitle);
        // Show the alert
        $this->addCommand(array('cmd' => 'toastr.' . $sType), $aOptions);
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
        return $this->alert($sMessage, $sTitle, 'info');
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

    public function remove()
    {
        $this->response()->script('toastr.remove()');
    }

    public function clear()
    {
        $this->response()->script('toastr.clear()');
    }
}
