/*
 * Bootstrap dialogs plugin
 */
jaxon.dialogs.bootstrap = {
    show: (args) => BootstrapDialog.show({
        ...args.data,
        buttons: args.data.buttons.map(button => {
            return {
                ...button,
                action: button.action !== 'close' ? new Function(button.action) : dialog => dialog.close(),
            };
        }),
    }),
    hide: (args) => BootstrapDialog.closeAll(),
    alert: (args) => {
        var dataTypes = {
            success: BootstrapDialog.TYPE_SUCCESS,
            info: BootstrapDialog.TYPE_INFO,
            warning: BootstrapDialog.TYPE_WARNING,
            danger: BootstrapDialog.TYPE_DANGER
        };
        args.data.type = dataTypes[args.data.type];
        BootstrapDialog.alert(args.data);
    },
    success: (content, title) => BootstrapDialog.alert({type: BootstrapDialog.TYPE_SUCCESS, message: content, title: title}),
    info: (content, title) => BootstrapDialog.alert({type: BootstrapDialog.TYPE_INFO, message: content, title: title}),
    warning: (content, title) => BootstrapDialog.alert({type: BootstrapDialog.TYPE_WARNING, message: content, title: title}),
    error: (content, title) => BootstrapDialog.alert({type: BootstrapDialog.TYPE_DANGER, message: content, title: title}),
    confirm: (question, title, yesCallback, noCallback) => BootstrapDialog.confirm({
        title: title,
        message: question,
        btnOKLabel: "<?php echo $this->yes ?>",
        btnCancelLabel: "<?php echo $this->no ?>",
        callback: (res) => {
            if(res)
                yesCallback();
            else if(noCallback !== undefined)
                noCallback();
        }
    }),
};
