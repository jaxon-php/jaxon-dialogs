<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.jalert.success;
    jaxon.ajax.message.info = jaxon.dialogs.jalert.info;
    jaxon.ajax.message.warning = jaxon.dialogs.jalert.warning;
    jaxon.ajax.message.error = jaxon.dialogs.jalert.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.jalert.confirm;
<?php endif ?>
    jaxon.command.handler.register("jalert.alert", function(args) {
        $.jAlert(args.data);
    });
