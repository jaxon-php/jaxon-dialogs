<?php

/**
 * Library.php - Base class for javascript library adapters.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries;

use Jaxon\Dialogs\Interfaces\Plugin;

class Library implements Plugin
{
    /**
     * The plugin instance
     *
     * @var object
     */
    protected $xDialog = null;

    /**
     * The name of the plugin
     *
     * @var string
     */
    protected $sName = null;

    /**
     * The object used to build the response that will be sent to the client browser
     *
     * @var \Jaxon\Response\Response
     */
    protected $xResponse;
    
    /**
     * Set the <Jaxon\Response\Response> object
     *
     * @param array             $xResponse              The response
     *
     * @return void
     */
    final public function setResponse($xResponse)
    {
        $this->xResponse = $xResponse;
    }
    
    /**
     * Get the <Jaxon\Response\Response> object
     *
     * @return object
     */
    final public function response()
    {
        return $this->xResponse;
    }
    
    /**
     * Add a client side plugin command to the response object
     *
     * @param array             $aAttributes            The attributes of the command
     * @param string            $sData                  The data to be added to the command
     *
     * @return void
     */
    final public function addCommand($aAttributes, $sData)
    {
        $this->xResponse->addPluginCommand($this, $aAttributes, $sData);
    }

    /**
     * Set the plugin instance
     *
     * @param Jaxon\Dialogs\Dialog      $xDialog        The plugin instance
     *
     * @return void
     */
    final public function setDialog($xDialog)
    {
        $this->xDialog = $xDialog;
        $this->setResponse($xDialog->response());
    }

    /**
     * Get the value of a config option
     *
     * @param string        $sName            The option name
     * @param mixed         $xDefault         The default value, to be returned if the option is not defined
     *
     * @return mixed        The option value, or its default value
     */
    final public function getOption($sName, $xDefault = null)
    {
        $sName = 'dialogs.' . $this->getName() . '.' . $sName;
        return $this->xDialog->getOption($sName, $xDefault);
    }
    
    /**
     * Check the presence of a config option
     *
     * @param string        $sName            The option name
     *
     * @return bool        True if the option exists, and false if not
     */
    final public function hasOption($sName)
    {
        $sName = 'dialogs.' . $this->getName() . '.' . $sName;
        return $this->xDialog->hasOption($sName);
    }
    
    /**
     * Get the names of the options matching a given prefix
     *
     * @param string        $sPrefix            The prefix to match
     *
     * @return array        The options matching the prefix
     */
    final public function getOptionNames($sPrefix)
    {
        $sPrefix = trim($sPrefix);
        $sPrefix = rtrim($sPrefix, '.') . '.';
        // The options names are relative to the plugin in Dialogs configuration 
        $sPrefix = 'dialogs.' . $this->getName() . '.' . $sPrefix;
        $nPrefixLength = strlen($sPrefix);
        $aOptionNames = $this->xDialog->getOptionNames($sPrefix);
        // Remove the prefix from the options names
        array_walk($aOptionNames, function(&$sName) use ($nPrefixLength) {$sName = substr($sName, $nPrefixLength);});
        return $aOptionNames;
    }

    /**
     * Get the names of the options matching a given prefix
     *
     * @param string        $sPrefix            The prefix to match
     *
     * @return array        The options matching the prefix
     */
    final public function getOptionScript($sVarPrefix, $sKeyPrefix, $nSpaces = 0)
    {
        $aOptions = $this->getOptionNames($sKeyPrefix);
        $sSpaces = str_repeat(' ', $nSpaces);
        $sScript = '';
        foreach($aOptions as $sName)
        {
            $value = $this->getOption($sKeyPrefix . $sName);
            if(is_string($value))
            {
                $value = "'$value'";
            }
            else if(is_bool($value))
            {
                $value = ($value ? 'true' : 'false');
            }
            else if(!is_numeric($value))
            {
                $value = print_r($value, true);
            }
            $sScript .= "\n" . $sSpaces . $sVarPrefix . $sName . ' = ' . $value . ';';
        }
        return $sScript;
    }

    /**
     * Set the plugin name
     *
     * @param string            $sName          The plugin name
     *
     * @return void
     */
    public function setName($sName)
    {
        $this->sName = $sName;
    }

    /**
     * Get the plugin name
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getName()
    {
        return $this->sName;
    }

    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return '';
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getCss()
    {
        return '';
    }

    /**
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Interfaces\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        return '';
    }
}
