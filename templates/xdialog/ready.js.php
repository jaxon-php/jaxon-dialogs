<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.xdialog.success;
    jaxon.ajax.message.info = jaxon.dialogs.xdialog.info;
    jaxon.ajax.message.warning = jaxon.dialogs.xdialog.warning;
    jaxon.ajax.message.error = jaxon.dialogs.xdialog.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.xdialog.confirm;
<?php endif ?>
    jaxon.command.handler.register("xdialog.show", jaxon.dialogs.xdialog.show);
    jaxon.command.handler.register("xdialog.hide", jaxon.dialogs.xdialog.hide);
    jaxon.command.handler.register("xdialog.success", function(args) {
        jaxon.dialogs.xdialog.success(args.data.body, args.data.title);
    });
    jaxon.command.handler.register("xdialog.info", function(args) {
        jaxon.dialogs.xdialog.info(args.data.body, args.data.title);
    });
    jaxon.command.handler.register("xdialog.warning", function(args) {
        jaxon.dialogs.xdialog.warning(args.data.body, args.data.title);
    });
    jaxon.command.handler.register("xdialog.error", function(args) {
        jaxon.dialogs.xdialog.error(args.data.body, args.data.title);
    });
