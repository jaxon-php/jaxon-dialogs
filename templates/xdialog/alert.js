jaxon.dialogs.xdialog = {
    show: function(args) {
        jaxon.dialogs.xdialog.dialog = xdialog.open({
            ...args.data,
            onok: args.data.onok ? new Function(args.data.onok) : null,
            oncancel: args.data.oncancel ? new Function(args.data.oncancel) : null,
        });
    },
    hide: function() {
        // Hide modal
        jaxon.dialogs.xdialog.dialog.close();
    },
    success: function(body, title = 'Success') {
        xdialog.alert(body, {title});
    },
    info: function(body, title = 'Information') {
        xdialog.info(body, {title});
    },
    warning: function(body, title = 'Warning') {
        xdialog.warn(body, {title});
    },
    error: function(body, title = 'Error') {
        xdialog.error(body, {title});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        xdialog.confirm(question, yesCallback, {
            title,
            buttons: {
                ok: "<?php echo $this->yes ?>",
                cancel: "<?php echo $this->no ?>",
            },
            oncancel: noCallback,
        });
    }
};
