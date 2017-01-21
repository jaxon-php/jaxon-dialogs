<?php

/**
 * Dialog.php - Modal, Alert and confirmation dialogs for Jaxon.
 *
 * Show modal, alert and confirmation dialogs with various javascript libraries
 * based on user settings.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs;

use Jaxon\Plugin\Response;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;
use Jaxon\Utils\Interfaces\EventListener;

class Dialog extends Response implements Modal, Alert, Confirm, EventListener
{
    use \Jaxon\Utils\Traits\Container;
    use \Jaxon\Utils\Traits\Event;

    /**
     * Dependency Injection manager
     *
     * @var object
     */
    protected $di;

    /**
     * Javascript dialog library adapters
     *
     * @var array
     */
    protected $aLibraries = array(
        // Bootbox
        'bootbox'       => \Jaxon\Dialogs\Libraries\Bootbox\Plugin::class,
        // Bootstrap
        'bootstrap'     => \Jaxon\Dialogs\Libraries\Bootstrap\Plugin::class,
        // PgwJS
        'pgwjs'         => \Jaxon\Dialogs\Libraries\PgwJS\Plugin::class,
        // Toastr
        'toastr'        => \Jaxon\Dialogs\Libraries\Toastr\Plugin::class,
        // JAlert
        'jalert'        => \Jaxon\Dialogs\Libraries\JAlert\Plugin::class,
        // Tingle
        'tingle'        => \Jaxon\Dialogs\Libraries\Tingle\Plugin::class,
        // SimplyToast
        'simply'        => \Jaxon\Dialogs\Libraries\SimplyToast\Plugin::class,
        // Noty
        'noty'          => \Jaxon\Dialogs\Libraries\Noty\Plugin::class,
        // Notify
        'notify'        => \Jaxon\Dialogs\Libraries\Notify\Plugin::class,
        // Lobibox
        'lobibox'       => \Jaxon\Dialogs\Libraries\Lobibox\Plugin::class,
        // Overhang
        'overhang'      => \Jaxon\Dialogs\Libraries\Overhang\Plugin::class,
        // PNotify
        'pnotify'       => \Jaxon\Dialogs\Libraries\PNotify\Plugin::class,
        // SweetAlert
        'sweetalert'    => \Jaxon\Dialogs\Libraries\SweetAlert\Plugin::class,
        // JQuery Confirm
        'jconfirm'      => \Jaxon\Dialogs\Libraries\JQueryConfirm\Plugin::class,
        // IziModal and IziToast
        // 'izi.modal'     => \Jaxon\Dialogs\Libraries\Izi\Modal::class, // Not yet ready
        'izi.toast'     => \Jaxon\Dialogs\Libraries\Izi\Toast::class,
    );
    
    /**
     * The constructor
     */
    public function __construct()
    {
        $this->di = new \Pimple\Container();
        $this->registerLibraries();
    }

    /**
     * Get the name of the plugin.
     *
     * @return string
     */
    public function getName()
    {
        return 'dialog';
    }

    /**
     * Get the hash value of the plugin.
     * 
     * The version number is also the hash value.
     *
     * @return string
     */
    public function generateHash()
    {
        // The version number is used as hash
        return '2.0b1';
    }

    /**
     * Register the javascript libraries adapters in the DI container.
     *
     * @return void
     */
    protected function registerLibrary($sName, $sClass)
    {
        $sName = (string)$sName;
        $sClass = (string)$sClass;
        // Register the library in the DI container
        $this->di[$sName] = function($c) use ($sClass) {
            return new $sClass;
        };
    }

    /**
     * Register the javascript libraries adapters in the DI container.
     *
     * @return void
     */
    protected function registerLibraries()
    {
        // Register supported libraries in the DI container
        foreach($this->aLibraries as $sName => $sClass)
        {
            $this->registerLibrary($sName, $sClass);
        }
    }

    /**
     * Register the javascript libraries adapters in the DI container.
     *
     * @return void
     */
    public function registerClasses()
    {
        // Register user defined libraries in the DI container
        $aLibraries = $this->getOptionNames('dialogs.classes');
        foreach($aLibraries as $sShortName => $sFullName)
        {
            $this->registerLibrary($sShortName, $this->getOption($sFullName));
        }
    }

    /**
     * Get a library adapter by its name.
     * 
     * @param string            $sName          The name of the library adapter
     *
     * @return Libraries\Library
     */
    protected function getLibrary($sName)
    {
        try
        {
            $library = $this->di[$sName];
            $library->setName($sName);
            $library->setDialog($this);
        }
        catch(\Exception $e)
        {
            $library = null;
        }
        return $library;
    }

    /**
     * Get the configured library adapter for modals.
     *
     * @return Libraries\Library|null
     */
    public function getModal()
    {
        if(!($sName = $this->getOption('dialogs.default.modal', '')))
        {
            return null;
        }
        // Get the current modal library
        $library = $this->getLibrary($sName);
        if(!($library) || !($library instanceof Modal))
        {
            return null;
        }
        return $library;
    }
    
    /**
     * Get the configured library adapter for alerts.
     *
     * @return Libraries\Library|null
     */
    public function getAlert()
    {
        if(!($sName = $this->getOption('dialogs.default.alert', '')))
        {
            return null;
        }
        // Get the current alert library
        $library = $this->getLibrary($sName);
        if(!($library) || !($library instanceof Alert))
        {
            return null;
        }
        return $library;
    }
    
    /**
     * Get the configured library adapter for confirmation.
     * 
     * @param bool              $bReturnDefault             Return the default confirm if none is configured
     *
     * @return Libraries\Library|null
     */
    public function getConfirm($bReturnDefault = false)
    {
        if(!($sName = $this->getOption('dialogs.default.confirm', '')))
        {
            return ($bReturnDefault ? $this->getPluginManager()->getDefaultConfirm() : null);
        }
        // Get the current confirm library
        $library = $this->getLibrary($sName);
        if(!($library) || !($library instanceof Confirm))
        {
            return ($bReturnDefault ? $this->getPluginManager()->getDefaultConfirm() : null);
        }
        return $library;
    }

    /**
     * Get the list of library adapters that are present in the configuration.
     *
     * @return array
     */
    protected function getInUseLibraries()
    {
        $aNames = $this->getOption('dialogs.libraries', array());
        if(!is_array($aNames))
        {
            $aNames = array();
        }
        $libraries = array();
        foreach($aNames as $sName)
        {
            if(($library = $this->getLibrary($sName)))
            {
                $libraries[$library->getName()] = $library;
            }
        }
        if(($library = $this->getModal()))
        {
            $libraries[$library->getName()] = $library;
        }
        if(($library = $this->getAlert()))
        {
            $libraries[$library->getName()] = $library;
        }
        if(($library = $this->getConfirm()))
        {
            $libraries[$library->getName()] = $library;
        }
        return $libraries;
    }

    /**
     * Return the javascript header code and file includes
     *
     * @return string
     */
    public function getJs()
    {
        if(!$this->includeAssets())
        {
            return '';
        }
        $libraries = $this->getInUseLibraries();
        $code = '';
        foreach($libraries as $library)
        {
            $code .= "\n" . $library->getJs() . "\n";
        }
        return $code;
    }

    /**
     * Return the CSS header code and file includes
     *
     * @return string
     */
    public function getCss()
    {
        if(!$this->includeAssets())
        {
            return '';
        }
        $libraries = $this->getInUseLibraries();
        $code = '';
        foreach($libraries as $library)
        {
            $code .= $library->getCss() . "\n";
        }
        return $code;
    }

    /**
     * Returns the Jaxon Javascript header and wrapper code to be printed into the page
     *
     * @return string
     */
    public function getScript()
    {
        $libraries = $this->getInUseLibraries();
        $code = '';
        foreach($libraries as $library)
        {
            $code .= $library->getScript() . "\n";
        }
        return $code;
    }

    /**
     * Show a modal dialog.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     * 
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     * 
     * Each button is an array containin the following entries:
     * - title: the text to be printed in the button
     * - class: the CSS class of the button
     * - click: the javascript function to be called when the button is clicked
     * If the click value is set to "close", then the buttons closes the dialog.
     * 
     * The content of the $options depends on the javascript library in use.
     * Check their specific documentation for more information.
     * 
     * @return void
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        $this->getModal()->show($title, $content, $buttons, $options);
    }

    /**
     * Show a modal dialog.
     * 
     * It is another name for the show() function.
     * 
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     * 
     * @return void
     */
    public function modal($title, $content, array $buttons, array $options = array())
    {
        $this->show($title, $content, $buttons, $options);
    }

    /**
     * Hide the modal dialog.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Modal interface.
     * 
     * @return void
     */
    public function hide()
    {
        $this->getModal()->hide();
    }

    /**
     * Print a success message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function success($message, $title = null)
    {
        $this->getAlert()->success($message, $title);
    }

    /**
     * Print an information message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function info($message, $title = null)
    {
        $this->getAlert()->info($message, $title);
    }

    /**
     * Print a warning message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function warning($message, $title = null)
    {
        $this->getAlert()->warning($message, $title);
    }

    /**
     * Print an error message.
     * 
     * It is a function of the Jaxon\Dialogs\Interfaces\Alert interface.
     * 
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     * 
     * @return void
     */
    public function error($message, $title = null)
    {
        $this->getAlert()->error($message, $title);
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
        return $this->getConfirm(true)->confirm($question, $yesScript, $noScript);
    }

    /**
     * Return an array of events to listen to.
     *
     * The array keys are event names and the value is the method name to call.
     * For instance:
     *  ['eventType' => 'methodName']
     *
     * @return array The event names to listen to
     */
    public function getEvents()
    {
        // The registerClasses() method needs to read the 'dialog.classes' option value.
        // So it must be called after the config is set.
        return ['post.config' => 'registerClasses'];
    }
}
