<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.noty.success;
    jaxon.ajax.message.info = jaxon.dialogs.noty.info;
    jaxon.ajax.message.warning = jaxon.dialogs.noty.warning;
    jaxon.ajax.message.error = jaxon.dialogs.noty.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.noty.confirm;
<?php endif ?>
    jaxon.command.handler.register('noty.alert', function(args) {
        noty({text: args.data.text, type: args.data.type, layout: 'topCenter', timeout: 5000});
    });
