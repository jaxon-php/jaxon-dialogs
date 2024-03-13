<?php echo $this->options ?>
<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.toastr.success;
    jaxon.ajax.message.info = jaxon.dialogs.toastr.info;
    jaxon.ajax.message.warning = jaxon.dialogs.toastr.warning;
    jaxon.ajax.message.error = jaxon.dialogs.toastr.error;
<?php endif ?>
    jaxon.command.handler.register("toastr.info", (args) =>
        jaxon.dialogs.toastr.info(args.data.message, args.data.title));
    jaxon.command.handler.register("toastr.success", (args) =>
        jaxon.dialogs.toastr.success(args.data.message, args.data.title));
    jaxon.command.handler.register("toastr.warning", (args) =>
        jaxon.dialogs.toastr.warning(args.data.message, args.data.title));
    jaxon.command.handler.register("toastr.error", (args) =>
        jaxon.dialogs.toastr.error(args.data.message, args.data.title));
    jaxon.command.handler.register("toastr.remove", jaxon.dialogs.toastr.remove);
    jaxon.command.handler.register("toastr.clear", jaxon.dialogs.toastr.clear);
