<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.cutealert.success;
    jaxon.ajax.message.info = jaxon.dialogs.cutealert.info;
    jaxon.ajax.message.warning = jaxon.dialogs.cutealert.warning;
    jaxon.ajax.message.error = jaxon.dialogs.cutealert.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.cutealert.confirm;
<?php endif ?>
    jaxon.command.handler.register("cutealert.alert", function(args) {
        cuteAlert(args.data);
    });
