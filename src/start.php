<?php

use Jaxon\Dialogs\Dialog;

$jaxon = jaxon();
// Register the template dir into the template renderer
$jaxon->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/templates');
// Register an instance of this plugin
$jaxon->di()->auto(Dialog::class);
$jaxon->registerPlugin(Dialog::class, 'dialog');
