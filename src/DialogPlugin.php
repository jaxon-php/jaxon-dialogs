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
use Jaxon\Di\Container;
use Jaxon\Dialogs\Libraries\AbstractDialogLibrary;
use Jaxon\Plugin\ResponsePlugin;
use Jaxon\Ui\Dialogs\DialogFacade;
use Jaxon\Ui\Dialogs\MessageInterface;
use Jaxon\Ui\Dialogs\ModalInterface;
use Jaxon\Ui\Dialogs\QuestionInterface;
use Jaxon\Utils\Template\TemplateEngine;

use function array_merge;
use function array_reduce;
use function dirname;

class DialogPlugin extends ResponsePlugin
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
     * @var DialogFacade
     */
    protected $xDialogFacade;

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
        'bootbox'       => Libraries\Bootbox\BootboxLibrary::class,
        // Bootstrap
        'bootstrap'     => Libraries\Bootstrap\BootstrapLibrary::class,
        // PgwJS
        'pgwjs'         => Libraries\PgwJS\PgwJsLibrary::class,
        // Toastr
        'toastr'        => Libraries\Toastr\ToastrLibrary::class,
        // JAlert
        'jalert'        => Libraries\JAlert\JAlertLibrary::class,
        // Tingle
        'tingle'        => Libraries\Tingle\TingleLibrary::class,
        // SimplyToast
        'simply'        => Libraries\SimplyToast\SimplyToastLibrary::class,
        // Noty
        'noty'          => Libraries\Noty\NotyLibrary::class,
        // Notify
        'notify'        => Libraries\Notify\NotifyLibrary::class,
        // Lobibox
        'lobibox'       => Libraries\Lobibox\LobiboxLibrary::class,
        // Overhang
        'overhang'      => Libraries\Overhang\OverhangLibrary::class,
        // PNotify
        'pnotify'       => Libraries\PNotify\PNotifyLibrary::class,
        // SweetAlert
        'sweetalert'    => Libraries\SweetAlert\SweetAlertLibrary::class,
        // JQuery Confirm
        'jconfirm'      => Libraries\JQueryConfirm\JQueryConfirmLibrary::class,
    );

    /**
     * The name of the library to use for the next call
     *
     * @var string
     */
    protected $sNextLibrary = '';

    /**
     * @vr array
     */
    protected $aLibrariesInUse = [];

    /**
     * The constructor
     *
     * @param Container $di
     * @param ConfigManager $xConfigManager
     * @param TemplateEngine $xTemplateEngine The template engine
     * @param DialogFacade $xDialogFacade
     */
    public function __construct(Container $di, ConfigManager $xConfigManager,
        TemplateEngine $xTemplateEngine, DialogFacade $xDialogFacade)
    {
        $this->xDialogFacade = $xDialogFacade;
        $this->di = $di;
        $this->xConfigManager = $xConfigManager;
        $this->xTemplateEngine = $xTemplateEngine;

        // Register the template dir into the template renderer
        $xTemplateEngine->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/templates');

        $this->registerLibraries();
        $this->registerClasses();
        // Get the default modal library
        if(($sName = $this->xConfigManager->getOption('dialogs.default.modal', '')))
        {
            $this->aLibrariesInUse[] = $sName;
            $xDialogFacade->setModalLibrary($sName);
        }
        // Get the configured message library
        if(($sName = $this->xConfigManager->getOption('dialogs.default.message', '')))
        {
            $this->aLibrariesInUse[] = $sName;
            $xDialogFacade->setMessageLibrary($sName);
        }
        // Get the configured question library
        if(($sName = $this->xConfigManager->getOption('dialogs.default.question', '')))
        {
            $this->aLibrariesInUse[] = $sName;
            $xDialogFacade->setQuestionLibrary($sName);
        }
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
     * @return AbstractDialogLibrary|null
     */
    public function getLibrary(string $sName): ?AbstractDialogLibrary
    {
        return $this->di->h($sName) ? $this->di->g($sName) : null;
    }

    /**
     * Set the library to use for the next call.
     *
     * @param string $sLibrary The name of the library
     *
     * @return DialogPlugin
     */
    public function with(string $sLibrary): DialogPlugin
    {
        $this->sNextLibrary = $sLibrary;
        return $this;
    }

    /**
     * Get the library adapter to use for modals.
     *
     * @return ModalInterface|null
     */
    protected function getModalLibrary(): ?ModalInterface
    {
        $xLibrary = $this->xDialogFacade->getModalLibrary($this->xResponse, $this->sNextLibrary);
        $this->sNextLibrary = '';
        return $xLibrary;
    }

    /**
     * Get the library adapter to use for messages.
     *
     * @return MessageInterface|null
     */
    protected function getMessageLibrary(): ?MessageInterface
    {
        $xLibrary = $this->xDialogFacade->getMessageLibrary(false, $this->xResponse, $this->sNextLibrary);
        $this->sNextLibrary = '';
        return $xLibrary;
    }

    /**
     * Get the library adapter to use for question.
     *
     * @return QuestionInterface|null
     */
    protected function getQuestionLibrary(): ?QuestionInterface
    {
        $xLibrary = $this->xDialogFacade->getQuestionLibrary($this->sNextLibrary);
        $this->sNextLibrary = '';
        return $xLibrary;
    }

    /**
     * Get the list of library adapters that are present in the configuration.
     *
     * @return array
     */
    protected function getLibrariesInUse(): array
    {
        $aNames = array_merge($this->aLibrariesInUse,
            $this->xConfigManager->getOption('dialogs.libraries', []));
        $aLibraries = [];
        foreach($aNames as $sName)
        {
            if(($xLibrary = $this->getLibrary($sName)))
            {
                $aLibraries[$xLibrary->getName()] = $xLibrary;
            }
        }
        return $aLibraries;
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return array_reduce($this->getLibrariesInUse(), function($sCode, $xLibrary) {
            return $sCode . $xLibrary->getJs() . "\n\n";
        }, '');
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return array_reduce($this->getLibrariesInUse(), function($sCode, $xLibrary) {
            return $sCode . $xLibrary->getCss() . "\n\n";
        }, '');
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return array_reduce($this->getLibrariesInUse(), function($sCode, $xLibrary) {
            return $sCode . $xLibrary->getScript() . "\n\n";
        }, "jaxon.dialogs = {};\n");
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return array_reduce($this->getLibrariesInUse(), function($sCode, $xLibrary) {
            return $sCode . $xLibrary->getReadyScript() . "\n\n";
        }, '');
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
     * @inheritDoc
     */
    public function success(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary()->success($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function info(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary()->info($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary()->warning($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function error(string $sMessage, string $sTitle = ''): string
    {
        return $this->getMessageLibrary()->error($sMessage, $sTitle);
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $sQuestion, string $sYesScript, string $sNoScript): string
    {
        return $this->getQuestionLibrary()->confirm($sQuestion, $sYesScript, $sNoScript);
    }
}
