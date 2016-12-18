<?php

namespace Jaxon\Dialogs\Libraries;

use Jaxon\Request\Interfaces\Confirm;

class Manager
{
    use \Jaxon\Utils\Traits\Container;

    // The Dependency Injection Container
    protected $di;

    protected $dialog = null;

    public function __construct($dialog)
    {
        $this->dialog = $dialog;
        $this->di = new \Pimple\Container();
    }

    public function getModal()
    {
        if(!($name = $this->getOption('dialogs.library.modal', '')))
        {
            return null;
        }
        $library = $this->di[$name];
        if($library instanceof \Jaxon\Dialogs\Interfaces\Modal)
        {
            $library->setName($name);
            $library->setDialog($this->dialog);
            return $library;
        }
        return null;
    }

    public function getAlert()
    {
        if(!($name = $this->getOption('dialogs.library.alert', '')))
        {
            return null;
        }
        $library = $this->di[$name];
        if($library instanceof \Jaxon\Dialogs\Interfaces\Alert)
        {
            $library->setName($name);
            $library->setDialog($this->dialog);
            return $library;
        }
        return null;
    }

    public function getConfirm()
    {
        if(!($name = $this->getOption('dialogs.library.confirm', '')))
        {
            return null;
        }
        $library = $this->di[$name];
        if($library instanceof \Jaxon\Request\Interfaces\Confirm)
        {
            $library->setName($name);
            $library->setDialog($this->dialog);
            return $library;
        }
        return null;
    }

    public function registerLibraries()
    {
        // Libraries
        $aLibraries = array(
            // Bootbox
            '' => \Jaxon\Dialogs\Libraries\Bootbox\Plugin::class,
        );
        $this->di['bootbox'] = function($c){
            return new \Jaxon\Dialogs\Libraries\Bootbox\Plugin();
        };
        // Bootstrap
        $this->di['bootstrap'] = function($c){
            return new \Jaxon\Dialogs\Libraries\Bootstrap\Plugin();
        };
        // PgwJS
        $this->di['pgwjs'] = function($c){
            return new \Jaxon\Dialogs\Libraries\PgwJS\Plugin();
        };
        // Toastr
        $this->di['toastr'] = function($c){
            return new \Jaxon\Dialogs\Libraries\Toastr\Plugin();
        };
        // JAlert
        $this->di['jalert'] = function($c){
            return new \Jaxon\Dialogs\Libraries\JAlert\Plugin();
        };

        foreach($aLibraries as $sName => $sClass)
        {
            $this->di[$sName] = function($c){
                return new $sClass;
            };
        }

        // Set confirmation dialog
        if(($confirm = $this->getConfirm()))
        {
            $this->getPluginManager()->setConfirm($confirm);
        }
    }
}
