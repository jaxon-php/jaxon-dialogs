<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.overhang.success;
    jaxon.ajax.message.info = jaxon.dialogs.overhang.info;
    jaxon.ajax.message.warning = jaxon.dialogs.overhang.warning;
    jaxon.ajax.message.error = jaxon.dialogs.overhang.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.overhang.confirm;
<?php endif ?>
    jaxon.command.handler.register("overhang.alert", function(args) {
        // Default options
        args.data.duration = 5;
        $("body").overhang(args.data);
    });
