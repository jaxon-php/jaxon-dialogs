<?php

/**
 * Library.php - Base class for javascript library adapters.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries;

use Jaxon\Dialogs\DialogPlugin;
use Jaxon\Dialogs\PluginInterface;
use Jaxon\Response\Response;

use function trim;
use function str_repeat;
use function is_string;
use function is_bool;
use function is_numeric;
use function json_encode;

class Library implements PluginInterface
{
    /**
     * The plugin instance
     *
     * @var DialogPlugin
     */
    protected $xDialogPlugin = null;

    /**
     * The name of the plugin
     *
     * @var string
     */
    protected $sName = '';

    /**
     * The subdir of the JS and CSS files in the CDN
     *
     * @var string
     */
    protected $sSubDir = '';

    /**
     * The default version of the plugin library
     *
     * @var string
     */
    protected $sVersion = '';

    /**
     * The default URI where to get the library files from
     *
     * @var string
     */
    protected $sUri = 'https://cdn.jaxon-php.org/libs';

    /**
     * The constructor
     *
     * @param string $sSubDir The subdir of the JS and CSS files in the CDN
     * @param string $sVersion The default version of the plugin library
     */
    protected function __construct(string $sSubDir, string $sVersion)
    {
        $this->sSubDir = $sSubDir;
        $this->sVersion = $sVersion;
    }

    /**
     * Add a client side plugin command to the response object
     *
     * @param array $aAttributes The attributes of the command
     * @param mixed $xData The data to be added to the command
     *
     * @return void
     */
    final public function addCommand(array $aAttributes, $xData)
    {
        $this->xResponse->addPluginCommand($this->xDialogPlugin, $aAttributes, $xData);
    }

    /**
     * Initialize the library class instance
     *
     * @param string $sName The plugin name
     * @param DialogPlugin $xDialogPlugin The DialogPlugin plugin instance
     *
     * @return void
     */
    final public function init(string $sName, DialogPlugin $xDialogPlugin)
    {
        // Set the library name
        $this->sName = $sName;
        // Set the dialog plugin
        $this->xDialogPlugin = $xDialogPlugin;
        // Set the default URI.
        $this->sUri = $xDialogPlugin->getOption('dialogs.lib.uri', $this->sUri);
        // Set the library URI.
        $this->sUri = rtrim($this->getOption('uri', $this->sUri), '/');
        // Set the subdir
        $this->sSubDir = trim($this->getOption('subdir', $this->sSubDir), '/');
        // Set the version number
        $this->sVersion = trim($this->getOption('version', $this->sVersion), '/');
    }

    /**
     * Get the value of a config option
     *
     * @param string $sName The option name
     * @param mixed $xDefault The default value, to be returned if the option is not defined
     *
     * @return mixed
     */
    final public function getOption(string $sName, $xDefault = null)
    {
        $sName = 'dialogs.' . $this->getName() . '.' . $sName;
        return $this->xDialogPlugin->getOption($sName, $xDefault);
    }

    /**
     * Check the presence of a config option
     *
     * @param string $sName The option name
     *
     * @return bool
     */
    final public function hasOption(string $sName): bool
    {
        $sName = 'dialogs.' . $this->getName() . '.' . $sName;
        return $this->xDialogPlugin->hasOption($sName);
    }

    /**
     * Get the names of the options matching a given prefix
     *
     * @param string $sPrefix The prefix to match
     *
     * @return array
     */
    final public function getOptionNames(string $sPrefix): array
    {
        // The options names are relative to the plugin in Dialogs configuration
        return $this->xDialogPlugin->getOptionNames('dialogs.' . $this->getName() . '.' . $sPrefix);
    }

    /**
     * Get the names of the options matching a given prefix
     *
     * @param string $sVarPrefix
     * @param string $sKeyPrefix
     * @param int $nSpaces
     *
     * @return string
     */
    final public function getOptionScript(string $sVarPrefix, string $sKeyPrefix, int $nSpaces = 4): string
    {
        $aOptions = $this->getOptionNames($sKeyPrefix);
        $sSpaces = str_repeat(' ', $nSpaces);
        $sScript = '';
        foreach($aOptions as $sShortName => $sFullName)
        {
            $value = $this->xDialogPlugin->getOption($sFullName);
            if(is_string($value))
            {
                $value = "'$value'";
            }
            elseif(is_bool($value))
            {
                $value = ($value ? 'true' : 'false');
            }
            elseif(!is_numeric($value))
            {
                $value = json_encode($value);
            }
            $sScript .= "\n" . $sSpaces . $sVarPrefix . $sShortName . ' = ' . $value . ';';
        }
        return $sScript;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->sName;
    }

    /**
     * @inheritDoc
     */
    public function getJs(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getCss(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript(): string
    {
        return '';
    }

    /**
     * Get the text of the "Yes" button for confirm dialog
     *
     * @return string
     */
    public function getQuestionTitle(): string
    {
        return $this->xDialogPlugin->getOption('dialogs.question.title', '');
    }

    /**
     * Get the text of the "Yes" button for confirm dialog
     *
     * @return string
     */
    public function getYesButtonText(): string
    {
        return $this->xDialogPlugin->getOption('dialogs.question.yes', 'Yes');
    }

    /**
     * Get the text of the "No" button for confirm dialog
     *
     * @return string
     */
    public function getNoButtonText(): string
    {
        return $this->xDialogPlugin->getOption('dialogs.question.no', 'No');
    }

    /**
     * Get the javascript HTML header code
     *
     * @param string $sFile The javascript file name
     *
     * @return string
     */
    public function getJsCode(string $sFile): string
    {
        return '<script type="text/javascript" src="' . $this->sUri . '/' .
            $this->sSubDir . '/' . $this->sVersion . '/' . $sFile . '"></script>';
    }

    /**
     * Get the CSS HTML header code
     *
     * @param string $sFile The CSS file name
     *
     * @return string
     */
    public function getCssCode(string $sFile): string
    {
        return '<link rel="stylesheet" href="' . $this->sUri . '/' .
            $this->sSubDir . '/' . $this->sVersion . '/' . $sFile . '" />';
    }

    /**
     * Render a template
     *
     * @param string $sTemplate The name of template to be rendered
     * @param array $aVars The template vars
     *
     * @return string
     */
    protected function render(string $sTemplate, array $aVars = []): string
    {
        // Is the library the default for alert messages?
        $isDefaultForMessage = ($this->getName() == $this->xDialogPlugin->getOption('dialogs.default.message'));
        // Is the library the default for confirm questions?
        $isDefaultForQuestion = ($this->getName() == $this->xDialogPlugin->getOption('dialogs.default.question'));
        $aLocalVars = [
            'yes' => $this->getYesButtonText(),
            'no' => $this->getNoButtonText(),
            'defaultForMessage' => $isDefaultForMessage,
            'defaultForQuestion' => $isDefaultForQuestion
        ];
        return $this->xDialogPlugin->render('jaxon::dialogs::' . $sTemplate, array_merge($aLocalVars, $aVars));
    }
}
