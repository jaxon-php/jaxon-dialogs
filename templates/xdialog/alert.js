jaxon.dialogs.xdialog = {
    show: (args) => {
        jaxon.dialogs.xdialog.dialog = xdialog.open({
            ...args.data,
            onok: args.data.onok ? new Function(args.data.onok) : null,
            oncancel: args.data.oncancel ? new Function(args.data.oncancel) : null,
        });
    },
    hide: () => jaxon.dialogs.xdialog.dialog.close(),
    success: (body, title = 'Success') => xdialog.alert(body, {title}),
    info: (body, title = 'Information') => xdialog.info(body, {title}),
    warning: (body, title = 'Warning') => xdialog.warn(body, {title}),
    error: (body, title = 'Error') => xdialog.error(body, {title}),
    confirm: (question, title, yesCallback, noCallback) => xdialog.confirm(question, yesCallback, {
        title,
        buttons: {
            ok: "<?php echo $this->yes ?>",
            cancel: "<?php echo $this->no ?>",
        },
        oncancel: noCallback ?? (() => {}),
    }),
};
