/*
 * Bootstrap dialogs plugin
 */
    jaxon.command.handler.register("bootstrap.show", jaxon.dialogs.bootstrap.show);
    jaxon.command.handler.register("bootstrap.hide", jaxon.dialogs.bootstrap.hide);
    jaxon.command.handler.register("bootstrap.alert", jaxon.dialogs.bootstrap.alert);
<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.bootstrap.success;
    jaxon.ajax.message.info = jaxon.dialogs.bootstrap.info;
    jaxon.ajax.message.warning = jaxon.dialogs.bootstrap.warning;
    jaxon.ajax.message.error = jaxon.dialogs.bootstrap.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.bootstrap.confirm;
<?php endif;
