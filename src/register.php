<?php

namespace Jaxon\Dialogs;

use Jaxon\Exception\SetupException;

use function Jaxon\jaxon;
use function php_sapi_name;

/**
 * @return void
 */
function register()
{
    // Do nothing if running in cli.
    if(php_sapi_name() === 'cli')
    {
        return;
    };

    $aLibraries = [
        Library\Alertify::class, // Alertify
        Library\Bootbox::class, // Bootbox
        Library\Bootstrap::class, // Bootstrap
        Library\Toastr::class, // Toastr
        Library\JAlert::class, // JAlert
        Library\Tingle::class, // Tingle
        Library\Noty::class, // Noty
        Library\Notify::class, // Notify
        Library\SweetAlert::class, // SweetAlert
        Library\JQueryConfirm::class, // JQuery Confirm
        Library\CuteAlert::class, // CuteAlert
    ];
    $jaxon = jaxon();
    $xDialog = $jaxon->dialog();
    foreach($aLibraries as $sClass)
    {
        try
        {
            $xDialog->registerLibrary($sClass, $sClass::NAME);
        }
        catch(SetupException $_){}
    }

    // Register the template dir into the template renderer
    $jaxon->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/lib');
}

register();
