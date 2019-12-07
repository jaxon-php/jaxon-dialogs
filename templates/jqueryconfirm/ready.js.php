<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.jconfirm.success;
    jaxon.ajax.message.info = jaxon.dialogs.jconfirm.info;
    jaxon.ajax.message.warning = jaxon.dialogs.jconfirm.warning;
    jaxon.ajax.message.error = jaxon.dialogs.jconfirm.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.jconfirm.confirm;
<?php endif ?>
    jaxon.command.handler.register("jconfirm.show", jaxon.dialogs.jconfirm.show);
    jaxon.command.handler.register("jconfirm.hide", jaxon.dialogs.jconfirm.hide);
    jaxon.command.handler.register("jconfirm.alert", function(args) {
        $.alert(args.data);
    });
