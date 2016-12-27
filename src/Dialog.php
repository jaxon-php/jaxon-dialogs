<?php

namespace Jaxon\Dialogs;

use Jaxon\Plugin\Response;
use Jaxon\Dialogs\Interfaces\Modal;
use Jaxon\Dialogs\Interfaces\Alert;
use Jaxon\Request\Interfaces\Confirm;

class Dialog extends Response implements Modal, Alert, Confirm
{
    use \Jaxon\Utils\Traits\Container;

    protected $di;
    // Libraries
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
    );
    
    public function __construct()
    {
        $this->di = new \Pimple\Container();
        $this->registerLibraries();
    }

    public function getName()
    {
        return 'dialog';
    }

    public function generateHash()
    {
        // The version number is used as hash
        return '1.1.0b1';
    }

    protected function getLibrary($name)
    {
        try
        {
            $library = $this->di[$name];
            $library->setName($name);
            $library->setDialog($this);
        }
        catch(\Exception $e)
        {
            $library = null;
        }
        return $library;
    }

    public function getModal()
    {
        if(!($name = $this->getOption('dialogs.default.modal', '')))
        {
            return null;
        }
        // Get the current modal library
        $library = $this->getLibrary($name);
        if(!($library) || !($library instanceof \Jaxon\Dialogs\Interfaces\Modal))
        {
            return null;
        }
        return $library;
    }
    
    public function getAlert()
    {
        if(!($name = $this->getOption('dialogs.default.alert', '')))
        {
            return null;
        }
        // Get the current alert library
        $library = $this->getLibrary($name);
        if(!($library) || !($library instanceof \Jaxon\Dialogs\Interfaces\Alert))
        {
            return null;
        }
        return $library;
    }
    
    public function getConfirm($bReturnDefault = false)
    {
        if(!($name = $this->getOption('dialogs.default.confirm', '')))
        {
            return ($bReturnDefault ? $this->getPluginManager()->getDefaultConfirm() : null);
        }
        // Get the current confirm library
        $library = $this->getLibrary($name);
        if(!($library) || !($library instanceof \Jaxon\Request\Interfaces\Confirm))
        {
            return ($bReturnDefault ? $this->getPluginManager()->getDefaultConfirm() : null);
        }
        return $library;
    }

    protected function getInUseLibraries()
    {
        $names = $this->getOption('dialogs.libraries', array());
        if(!is_array($names))
        {
            $names = array();
        }
        $libraries = array();
        foreach($names as $name)
        {
            if(($library = $this->getLibrary($name)))
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
            $code .= $library->getJs() . "\n";
        }
        return $code;
    }

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

    public function modal($title, $content, array $buttons, array $options = array())
    {
        $this->getModal()->modal($title, $content, $buttons, $options);
    }

    public function show($title, $content, array $buttons, array $options = array())
    {
        $this->getModal()->show($title, $content, $buttons, $options);
    }

    public function hide()
    {
        $this->getModal()->hide();
    }

    public function success($message, $title = null)
    {
        $this->getAlert()->success($message, $title);
    }

    public function info($message, $title = null)
    {
        $this->getAlert()->info($message, $title);
    }

    public function warning($message, $title = null)
    {
        $this->getAlert()->warning($message, $title);
    }

    public function error($message, $title = null)
    {
        $this->getAlert()->error($message, $title);
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question
     * 
     * This is the implementation of the Jaxon\Request\Interfaces\Confirm interface.
     * 
     * @return string
     */
    public function getScriptWithQuestion($question, $script)
    {
        return $this->getConfirm(true)->getScriptWithQuestion($question, $script);
    }
    
    public function registerLibraries()
    {
        // Register libraries in DI
        foreach($this->aLibraries as $sName => $sClass)
        {
            $this->di[$sName] = function($c) use ($sClass) {
                return new $sClass;
            };
        }
    }
}
