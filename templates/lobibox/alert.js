jaxon.dialogs.lobibox = {
    window: null,
    show: function(args) {
        // Add buttons
        for(key in args.data.buttons)
        {
            button = args.data.buttons[key];
            if(button.action == "close")
            {
                button.action = function(){return false;};
                button.closeOnClick = true;
            }
            else
            {
                button.action = new Function(button.action);
                button.closeOnClick = false;
            }
        }
        args.data.callback = function(lobibox, type){
            args.data.buttons[type].action();
        };
        if((jaxon.dialogs.lobibox.window))
        {
            jaxon.dialogs.lobibox.window.destroy();
        }
        jaxon.dialogs.lobibox.window = Lobibox.window(args.data);
    },
    hide: function(args) {
        if((jaxon.dialogs.lobibox.window))
        {
            jaxon.dialogs.lobibox.window.destroy();
        }
        jaxon.dialogs.lobibox.window = null;
    },
    success: function(content, title) {
        Lobibox.notify('success', {title: title, msg: content});
    },
    info: function(content, title) {
        Lobibox.notify('info', {title: title, msg: content});
    },
    warning: function(content, title) {
        Lobibox.notify('warning', {title: title, msg: content});
    },
    error: function(content, title) {
        Lobibox.notify('error', {title: title, msg: content});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        Lobibox.base.OPTIONS.buttons.yes.text = "<?php echo $this->yes ?>";
        Lobibox.base.OPTIONS.buttons.no.text = "<?php echo $this->no ?>";
        Lobibox.confirm({
            title: title,
            msg: question,
            callback: function(lobibox, type){
                if(type == "yes")
                    yesCallback();
                else if(noCallback != undefined)
                    noCallback();
            }
        });
    }
};
