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
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs;

use Jaxon\Plugin\Response;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Request\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;
use Jaxon\Utils\Interfaces\EventListener;

class Dialog extends Response implements Modal, Alert, Confirm, EventListener
{
    use \Jaxon\Utils\Traits\Template;
    use \Jaxon\Utils\Traits\Manager;
    use \Jaxon\Utils\Traits\Config;
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
        'bootbox'       => Libraries\Bootbox\Plugin::class,
        // Bootstrap
        'bootstrap'     => Libraries\Bootstrap\Plugin::class,
        // PgwJS
        'pgwjs'         => Libraries\PgwJS\Plugin::class,
        // Toastr
        'toastr'        => Libraries\Toastr\Plugin::class,
        // JAlert
        'jalert'        => Libraries\JAlert\Plugin::class,
        // Tingle
        'tingle'        => Libraries\Tingle\Plugin::class,
        // SimplyToast
        'simply'        => Libraries\SimplyToast\Plugin::class,
        // Noty
        'noty'          => Libraries\Noty\Plugin::class,
        // Notify
        'notify'        => Libraries\Notify\Plugin::class,
        // Lobibox
        'lobibox'       => Libraries\Lobibox\Plugin::class,
        // Overhang
        'overhang'      => Libraries\Overhang\Plugin::class,
        // PNotify
        'pnotify'       => Libraries\PNotify\Plugin::class,
        // SweetAlert
        'sweetalert'    => Libraries\SweetAlert\Plugin::class,
        // JQuery Confirm
        'jconfirm'      => Libraries\JQueryConfirm\Plugin::class,
        // IziModal and IziToast
        // 'izi-modal'     => Libraries\Izi\Modal::class, // Not yet ready
        'izi-toast'     => Libraries\Izi\Toast::class,
        // YmzBox
        'ymzbox'        => Libraries\YmzBox\Plugin::class,
    );

    /**
     * The name of the library to use for modals
     *
     * @var string
     */
    protected $sModalLibrary = null;

    /**
     * The name of the library to use for alerts
     *
     * @var string
     */
    protected $sAlertLibrary = null;

    /**
     * The name of the library to use for confirmation
     *
     * @var string
     */
    protected $sConfirmLibrary = null;

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
        return '2.0.2';
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
        $this->di[$sName] = function ($c) use ($sName, $sClass) {
            $xLibrary = new $sClass;
            $xLibrary->init($sName, $this->di['dialog']);
            return $xLibrary;
        };
    }

    /**
     * Register the javascript libraries adapters in the DI container.
     *
     * @return void
     */
    protected function registerLibraries()
    {
        // Register this instance of the plugin in the DI.
        $this->di['dialog'] = $this;
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
    public function getLibrary($sName)
    {
        try
        {
            $library = $this->di[$sName];
        }
        catch(\Exception $e)
        {
            $library = null;
        }
        return $library;
    }

    /**
     * Set the library adapter to use for modals.
     *
     * @param string            $sLibrary                   The name of the library adapter
     *
     * @return void
     */
    public function setModalLibrary($sLibrary)
    {
        $this->sModalLibrary = $sLibrary;
    }

    /**
     * Get the library adapter to use for modals.
     *
     * @return Libraries\Library|null
     */
    protected function getModalLibrary()
    {
        // Get the current modal library
        if(($this->sModalLibrary) &&
            ($library = $this->getLibrary($this->sModalLibrary)) && ($library instanceof Modal))
        {
            return $library;
        }
        // Get the default modal library
        if(($sName = $this->getOption('dialogs.default.modal', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof Modal))
        {
            return $library;
        }
        return null;
    }
    
    /**
     * Set the library adapter to use for alerts.
     *
     * @param string            $sLibrary                   The name of the library adapter
     *
     * @return void
     */
    public function setAlertLibrary($sLibrary)
    {
        $this->sAlertLibrary = $sLibrary;
    }

    /**
     * Get the library adapter to use for alerts.
     *
     * @return Libraries\Library|null
     */
    protected function getAlertLibrary($bReturnDefault = false)
    {
        // Get the current alert library
        if(($this->sAlertLibrary) &&
            ($library = $this->getLibrary($this->sAlertLibrary)) && ($library instanceof Alert))
        {
            return $library;
        }
        // Get the configured alert library
        if(($sName = $this->getOption('dialogs.default.alert', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof Alert))
        {
            return $library;
        }
        // Get the default alert library
        return ($bReturnDefault ? $this->getPluginManager()->getDefaultAlert() : null);
    }
    
    /**
     * Set the library adapter to use for confirmation.
     *
     * @param string            $sLibrary                   The name of the library adapter
     *
     * @return void
     */
    public function setConfirmLibrary($sLibrary)
    {
        $this->sConfirmLibrary = $sLibrary;
    }

    /**
     * Get the library adapter to use for confirmation.
     *
     * @param bool              $bReturnDefault             Return the default confirm if none is configured
     *
     * @return Libraries\Library|null
     */
    protected function getConfirmLibrary($bReturnDefault = false)
    {
        // Get the current confirm library
        if(($this->sConfirmLibrary) &&
            ($library = $this->getLibrary($this->sConfirmLibrary)) && ($library instanceof Confirm))
        {
            return $library;
        }
        // Get the configured confirm library
        if(($sName = $this->getOption('dialogs.default.confirm', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof Confirm))
        {
            return $library;
        }
        // Get the default confirm library
        return ($bReturnDefault ? $this->getPluginManager()->getDefaultConfirm() : null);
    }

    /**
     * Get the list of library adapters that are present in the configuration.
     *
     * @return array
     */
    protected function getLibrariesInUse()
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
        if(($library = $this->getModalLibrary()))
        {
            $libraries[$library->getName()] = $library;
        }
        if(($library = $this->getAlertLibrary()))
        {
            $libraries[$library->getName()] = $library;
        }
        if(($library = $this->getConfirmLibrary()))
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
        $libraries = $this->getLibrariesInUse();
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
        $libraries = $this->getLibrariesInUse();
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
        $libraries = $this->getLibrariesInUse();
        $code = "jaxon.dialogs = {};\n";
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
        $this->getModalLibrary()->show($title, $content, $buttons, $options);
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
        $this->getModalLibrary()->hide();
    }

    /**
     * Set the library to return the javascript code or run it in the browser.
     *
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     *
     * @param boolean             $bReturn              Whether to return the code
     *
     * @return void
     */
    public function setReturn($bReturn)
    {
        $this->getAlertLibrary(true)->setReturn($bReturn);
    }
    
    /**
     * Check if the library should return the js code or run it in the browser.
     *
     * It is a function of the Jaxon\Request\Interfaces\Alert interface.
     *
     * @return boolean
     */
    public function getReturn()
    {
        return $this->getAlertLibrary(true)->getReturn();
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
        return $this->getAlertLibrary(true)->success((string)$message, (string)$title);
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
        return $this->getAlertLibrary(true)->info((string)$message, (string)$title);
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
        return $this->getAlertLibrary(true)->warning((string)$message, (string)$title);
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
        return $this->getAlertLibrary(true)->error((string)$message, (string)$title);
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
        return $this->getConfirmLibrary(true)->confirm($question, $yesScript, $noScript);
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
