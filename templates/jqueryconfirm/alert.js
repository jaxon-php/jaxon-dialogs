jaxon.dialogs.jconfirm = {
    dialog: null,
    show: (args) => {
        // Add buttons
        for(key in args.data.buttons)
        {
            button = args.data.buttons[key];
            button.action = button.action !== 'close' ? new Function(button.action) :
                () => jaxon.dialogs.jconfirm.dialog.close();
        }
        args.data.closeIcon = true;
        if((jaxon.dialogs.jconfirm.dialog))
        {
            jaxon.dialogs.jconfirm.dialog.close();
        }
        jaxon.dialogs.jconfirm.dialog = $.confirm(args.data);
    },
    hide: (args) => {
        if((jaxon.dialogs.jconfirm.dialog))
        {
            jaxon.dialogs.jconfirm.dialog.close();
        }
        jaxon.dialogs.jconfirm.dialog = null;
    },
    success: (content, title) => $.alert({content: content, title: title, type: 'green', icon: 'fa fa-success'}),
    info: (content, title) => $.alert({content: content, title: title, type: 'blue', icon: 'fa fa-info'}),
    warning: (content, title) => $.alert({content: content, title: title, type: 'orange', icon: 'fa fa-warning'}),
    error: (content, title) => $.alert({content: content, title: title, type: 'red', icon: 'fa fa-error'}),
    confirm: (question, title, yesCallback, noCallback) => $.confirm({
        title: title,
        content: question,
        buttons: {
            yes: {
                btnClass: "btn-blue",
                text: "<?php echo $this->yes ?>",
                action: yesCallback
            },
            no: {
                text: "<?php echo $this->no ?>",
                action: noCallback ?? (() => {}),
            }
        }
    }),
};
