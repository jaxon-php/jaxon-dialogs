<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.notify.success;
    jaxon.ajax.message.info = jaxon.dialogs.notify.info;
    jaxon.ajax.message.warning = jaxon.dialogs.notify.warning;
    jaxon.ajax.message.error = jaxon.dialogs.notify.error;
<?php endif ?>
    jaxon.command.handler.register("notify.alert", function(args) {
        $.notify(args.data.message, {className: args.data.className, position: "top center"});
    });
