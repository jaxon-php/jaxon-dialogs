<?php

/**
 * Dialog.php - Modal, message and question dialogs for Jaxon.
 *
 * Show modal, message and question dialogs with various javascript libraries
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
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;
use Jaxon\Utils\Config\Config;
use Jaxon\Utils\Template\Engine as TemplateEngine;
use Exception;

class Dialog extends Response implements Modal, Message, Question
{
    /**
     * Dependency Injection manager
     *
     * @var object
     */
    protected $di;

    /**
     * @var Config
     */
    protected $xConfig;

    /**
     * The Jaxon template engine
     *
     * @var TemplateEngine
     */
    protected $xTemplateEngine;

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
     * The name of the library to use for messages
     *
     * @var string
     */
    protected $sMessageLibrary = null;

    /**
     * The name of the library to use for question
     *
     * @var string
     */
    protected $sQuestionLibrary = null;

    /**
     * The constructor
     *
     * @param Config $xConfig
     * @param TemplateEngine $xTemplateEngine      The template engine
     */
    public function __construct(Config $xConfig, TemplateEngine $xTemplateEngine)
    {
        $this->di = new \Pimple\Container();
        $this->xConfig = $xConfig;
        $this->xTemplateEngine = $xTemplateEngine;

        $this->registerLibraries();
        $this->registerClasses();
    }

    /**
     * Get the value of a config option
     *
     * @param string $sName The option name
     * @param mixed $xDefault The default value, to be returned if the option is not defined
     *
     * @return mixed
     */
    public function getOption(string $sName, $xDefault = null)
    {
        return $this->xConfig->getOption($sName, $xDefault);
    }

    /**
     * Check the presence of a config option
     *
     * @param string $sName The option name
     *
     * @return bool
     */
    public function hasOption(string $sName): bool
    {
        return $this->xConfig->hasOption($sName);
    }

    /**
     * Get the names of the options matching a given prefix
     *
     * @param string $sPrefix The prefix to match
     *
     * @return array
     */
    public function getOptionNames(string $sPrefix): array
    {
        return $this->xConfig->getOptionNames($sPrefix);
    }

    /**
     * Render a template
     *
     * @param string        $sTemplate            The name of template to be rendered
     * @param array         $aVars                The template vars
     *
     * @return string
     */
    public function render(string $sTemplate, array $aVars = []): string
    {
        return $this->xTemplateEngine->render($sTemplate, $aVars);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'dialog';
    }

    /**
     * @inheritDoc
     */
    public function getHash(): string
    {
        // The version number is used as hash
        return '3.1.0';
    }

    /**
     * Register the javascript libraries adapters in the DI container.
     *
     * @param string $sName
     * @param string $sClass
     *
     * @return void
     */
    protected function registerLibrary(string $sName, string $sClass)
    {
        // Register the library in the DI container
        $this->di[$sName] = function($di) use($sName, $sClass) {
            $xLibrary = new $sClass;
            $xLibrary->init($sName, $this);
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
    protected function registerClasses()
    {
        // Register user defined libraries in the DI container
        $aLibraries = $this->xConfig->getOptionNames('dialogs.classes');
        foreach($aLibraries as $sShortName => $sFullName)
        {
            $this->registerLibrary($sShortName, $this->xConfig->getOption($sFullName));
        }
    }

    /**
     * Get a library adapter by its name.
     *
     * @param string            $sName          The name of the library adapter
     *
     * @return Modal|Message|Question
     */
    public function getLibrary(string $sName)
    {
        try
        {
            return $this->di[$sName];
        }
        catch(Exception $e)
        {
            return null;
        }
    }

    /**
     * Set the library adapter to use for modals.
     *
     * @param string            $sLibrary                   The name of the library adapter
     *
     * @return void
     */
    public function setModalLibrary(string $sLibrary)
    {
        $this->sModalLibrary = $sLibrary;
    }

    /**
     * Get the library adapter to use for modals.
     *
     * @return Modal|null
     */
    protected function getModalLibrary(): ?Modal
    {
        // Get the current modal library
        if(($this->sModalLibrary) &&
            ($library = $this->getLibrary($this->sModalLibrary)) && ($library instanceof Modal))
        {
            return $library;
        }
        // Get the default modal library
        if(($sName = $this->xConfig->getOption('dialogs.default.modal', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof Modal))
        {
            return $library;
        }
        return null;
    }

    /**
     * Set the library adapter to use for messages.
     *
     * @param string            $sLibrary                   The name of the library adapter
     *
     * @return void
     */
    public function setMessageLibrary(string $sLibrary)
    {
        $this->sMessageLibrary = $sLibrary;
    }

    /**
     * Get the library adapter to use for messages.
     *
     * @param bool $bReturnDefault
     *
     * @return Message|null
     */
    protected function getMessageLibrary(bool $bReturnDefault = false): ?Message
    {
        // Get the current message library
        if(($this->sMessageLibrary) &&
            ($library = $this->getLibrary($this->sMessageLibrary)) && ($library instanceof Message))
        {
            return $library;
        }
        // Get the configured message library
        if(($sName = $this->xConfig->getOption('dialogs.default.message', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof Message))
        {
            return $library;
        }
        // Get the default message library
        return ($bReturnDefault ? jaxon()->dialog()->getDefaultMessage() : null);
    }

    /**
     * Set the library adapter to use for question.
     *
     * @param string            $sLibrary                   The name of the library adapter
     *
     * @return void
     */
    public function setQuestionLibrary(string $sLibrary)
    {
        $this->sQuestionLibrary = $sLibrary;
    }

    /**
     * Get the library adapter to use for question.
     *
     * @param bool              $bReturnDefault             Return the default confirm if none is configured
     *
     * @return Question|null
     */
    protected function getQuestionLibrary(bool $bReturnDefault = false): ?Question
    {
        // Get the current confirm library
        if(($this->sQuestionLibrary) &&
            ($library = $this->getLibrary($this->sQuestionLibrary)) && ($library instanceof Question))
        {
            return $library;
        }
        // Get the configured confirm library
        if(($sName = $this->xConfig->getOption('dialogs.default.question', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof Question))
        {
            return $library;
        }
        // Get the default confirm library
        return ($bReturnDefault ? jaxon()->dialog()->getDefaultQuestion() : null);
    }

    /**
     * Get the list of library adapters that are present in the configuration.
     *
     * @return array
     */
    protected function getLibrariesInUse(): array
    {
        $aNames = $this->xConfig->getOption('dialogs.libraries', []);
        if(!is_array($aNames))
        {
            $aNames = [];
        }
        $libraries = [];
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
        if(($library = $this->getMessageLibrary()))
        {
            $libraries[$library->getName()] = $library;
        }
        if(($library = $this->getQuestionLibrary()))
        {
            $libraries[$library->getName()] = $library;
        }
        return $libraries;
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        $libraries = $this->getLibrariesInUse();
        $code = '';
        foreach($libraries as $library)
        {
            $code .= "\n" . $library->getJs() . "\n";
        }
        return $code;
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        $libraries = $this->getLibrariesInUse();
        $code = '';
        foreach($libraries as $library)
        {
            $code .= $library->getCss() . "\n";
        }
        return $code;
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
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
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        $libraries = $this->getLibrariesInUse();
        $code = "";
        foreach($libraries as $library)
        {
            $code .= $library->getReadyScript() . "\n";
        }
        return $code;
    }

    /**
     * Show a modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Modal interface.
     *
     * @param string            $sTitle                  The title of the dialog
     * @param string            $sContent                The content of the dialog
     * @param array             $aButtons                The buttons of the dialog
     * @param array             $aOptions                The options of the dialog
     *
     * Each button is an array containin the following entries:
     * - title: the text to be printed in the button
     * - class: the CSS class of the button
     * - click: the javascript function to be called when the button is clicked
     * If the click value is set to "close", then the buttons closes the dialog.
     *
     * The content of the $aOptions depends on the javascript library in use.
     * Check their specific documentation for more information.
     *
     * @return void
     */
    public function show(string $sTitle, string $sContent, array $aButtons = [], array $aOptions = [])
    {
        $this->getModalLibrary()->show($sTitle, $sContent, $aButtons, $aOptions);
    }

    /**
     * Show a modal dialog.
     *
     * It is another name for the show() function.
     *
     * @param string            $sTitle                  The title of the dialog
     * @param string            $sContent                The content of the dialog
     * @param array             $aButtons                The buttons of the dialog
     * @param array             $aOptions                The options of the dialog
     *
     * @return void
     */
    public function modal(string $sTitle, string $sContent, array $aButtons = [], array $aOptions = [])
    {
        $this->show($sTitle, $sContent, $aButtons, $aOptions);
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
        $this->getModalLibrary()->hide();
    }

    /**
     * Set the library to return the javascript code or run it in the browser.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param boolean             $bReturn              Whether to return the code
     *
     * @return void
     */
    public function setReturn(bool $bReturn)
    {
        $this->getMessageLibrary(true)->setReturn($bReturn);
    }

    /**
     * Check if the library should return the js code or run it in the browser.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @return bool
     */
    public function getReturn(): bool
    {
        return $this->getMessageLibrary(true)->getReturn();
    }

    /**
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary(true)->success($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary(true)->info($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary(true)->warning($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary(true)->error($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        return $this->getQuestionLibrary(true)->confirm($sQuestion, $sYesScript, $sNoScript);
    }
}
