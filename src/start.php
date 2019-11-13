<?php
// Register the template dir into the template renderer
jaxon()->template()->addNamespace('jaxon::dialogs', dirname(__DIR__) . '/templates');
// Register an instance of this plugin
jaxon()->registerPlugin(new \Jaxon\Dialogs\Dialog());
