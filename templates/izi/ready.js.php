<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.izi.success;
    jaxon.ajax.message.info = jaxon.dialogs.izi.info;
    jaxon.ajax.message.warning = jaxon.dialogs.izi.warning;
    jaxon.ajax.message.error = jaxon.dialogs.izi.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.izi.confirm;
<?php endif ?>
    jaxon.command.handler.register("izitoast.success", function(args) {
        jaxon.dialogs.izi.success(args.data);
    });
    jaxon.command.handler.register("izitoast.info", function(args) {
        jaxon.dialogs.izi.info(args.data);
    });
    jaxon.command.handler.register("izitoast.warning", function(args) {
        jaxon.dialogs.izi.warning(args.data);
    });
    jaxon.command.handler.register("izitoast.error", function(args) {
        jaxon.dialogs.izi.error(args.data);
    });
