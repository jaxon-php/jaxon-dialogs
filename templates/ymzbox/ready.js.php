<?php echo $this->options ?>
<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.ymzbox.success;
    jaxon.ajax.message.info = jaxon.dialogs.ymzbox.info;
    jaxon.ajax.message.warning = jaxon.dialogs.ymzbox.warning;
    jaxon.ajax.message.error = jaxon.dialogs.ymzbox.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.ymzbox.confirm;
<?php endif ?>
    jaxon.command.handler.register("ymzbox.alert", function(args) {
        ymz.jq_toast(args.data);
    });
