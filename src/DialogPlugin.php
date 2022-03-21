<?php

/**
 * DialogPlugin.php - ModalInterface, message and question dialogs for Jaxon.
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

use Jaxon\Config\ConfigManager;
use Jaxon\Container\Container;
use Jaxon\Plugin\ResponsePlugin;
use Jaxon\Ui\Dialogs\Dialog;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\QuestionInterface;
use Jaxon\Utils\Template\Engine as TemplateEngine;

use Exception;

use function dirname;

class DialogPlugin extends ResponsePlugin implements ModalInterface, MessageInterface, QuestionInterface
{
    /**
     * @const The plugin name
     */
    const NAME = 'dialog';

    /**
     * Dependency Injection manager
     *
     * @var Container
     */
    protected $di;

    /**
     * @var Dialog
     */
    protected $xDialog;

    /**
     * @var ConfigManager
     */
    protected $xConfigManager;

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
     * @param Dialog $xDialog
     * @param Container $di
     * @param ConfigManager $xConfigManager
     * @param TemplateEngine $xTemplateEngine The template engine
     */
    public function __construct(Dialog $xDialog, Container $di, ConfigManager $xConfigManager, TemplateEngine $xTemplateEngine)
    {
        $this->xDialog = $xDialog;
        $this->di = $di;
        $this->xConfigManager = $xConfigManager;
        $this->xTemplateEngine = $xTemplateEngine;

        // Register the template dir into the template renderer
        $xTemplateEngine->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/templates');

        $this->registerLibraries();
        $this->registerClasses();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
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
        return $this->xConfigManager->getOption($sName, $xDefault);
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
        return $this->xConfigManager->hasOption($sName);
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
        return $this->xConfigManager->getOptionNames($sPrefix);
    }

    /**
     * Render a template
     *
     * @param string $sTemplate The name of template to be rendered
     * @param array $aVars The template vars
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
        $this->di->set($sName, function() use($sName, $sClass) {
            $xLibrary = new $sClass;
            $xLibrary->init($sName, $this);
            return $xLibrary;
        });
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
        $aLibraries = $this->xConfigManager->getOptionNames('dialogs.classes');
        foreach($aLibraries as $sShortName => $sFullName)
        {
            $this->registerLibrary($sShortName, $this->xConfigManager->getOption($sFullName));
        }
    }

    /**
     * Get a library adapter by its name.
     *
     * @param string $sName The name of the library adapter
     *
     * @return ModalInterface|MessageInterface|QuestionInterface
     */
    public function getLibrary(string $sName)
    {
        try
        {
            return $this->di->g($sName);
        }
        catch(Exception $e)
        {
            return null;
        }
    }

    /**
     * Set the library adapter to use for modals.
     *
     * @param string $sLibrary The name of the library adapter
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
     * @return ModalInterface|null
     */
    protected function getModalLibrary(): ?ModalInterface
    {
        // Get the current modal library
        if(($this->sModalLibrary) &&
            ($library = $this->getLibrary($this->sModalLibrary)) && ($library instanceof ModalInterface))
        {
            return $library;
        }
        // Get the default modal library
        if(($sName = $this->xConfigManager->getOption('dialogs.default.modal', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof ModalInterface))
        {
            return $library;
        }
        return null;
    }

    /**
     * Set the library adapter to use for messages.
     *
     * @param string $sLibrary The name of the library adapter
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
     * @return MessageInterface|null
     */
    protected function getMessageLibrary(bool $bReturnDefault = false): ?MessageInterface
    {
        // Get the current message library
        if(($this->sMessageLibrary) &&
            ($library = $this->getLibrary($this->sMessageLibrary)) &&
            ($library instanceof MessageInterface))
        {
            return $library;
        }
        // Get the configured message library
        if(($sName = $this->xConfigManager->getOption('dialogs.default.message', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof MessageInterface))
        {
            return $library;
        }
        // Get the default message library
        return ($bReturnDefault ? $this->xDialog->getDefaultMessage() : null);
    }

    /**
     * Set the library adapter to use for question.
     *
     * @param string $sLibrary The name of the library adapter
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
     * @param bool $bReturnDefault Return the default confirm if none is configured
     *
     * @return QuestionInterface|null
     */
    protected function getQuestionLibrary(bool $bReturnDefault = false): ?QuestionInterface
    {
        // Get the current confirm library
        if(($this->sQuestionLibrary) &&
            ($library = $this->getLibrary($this->sQuestionLibrary)) &&
            ($library instanceof QuestionInterface))
        {
            return $library;
        }
        // Get the configured confirm library
        if(($sName = $this->xConfigManager->getOption('dialogs.default.question', '')) &&
            ($library = $this->getLibrary($sName)) && ($library instanceof QuestionInterface))
        {
            return $library;
        }
        // Get the default confirm library
        return ($bReturnDefault ? $this->xDialog->getDefaultQuestion() : null);
    }

    /**
     * Get the list of library adapters that are present in the configuration.
     *
     * @return array
     */
    protected function getLibrariesInUse(): array
    {
        $aNames = $this->xConfigManager->getOption('dialogs.libraries', []);
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
     * It is a function of the Jaxon\Dialogs\Contracts\ModalInterface interface.
     *
     * @param string $sTitle The title of the dialog
     * @param string $sContent The content of the dialog
     * @param array $aButtons The buttons of the dialog
     * @param array $aOptions The options of the dialog
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
     * @param string $sTitle The title of the dialog
     * @param string $sContent The content of the dialog
     * @param array $aButtons The buttons of the dialog
     * @param array $aOptions The options of the dialog
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
     * It is a function of the Jaxon\Dialogs\Contracts\ModalInterface interface.
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
     * @param boolean $bReturn Whether to return the code
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
