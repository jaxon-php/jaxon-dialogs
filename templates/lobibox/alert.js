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

jaxon.dom.ready(function() {
    Lobibox.notify.DEFAULTS = $.extend({}, Lobibox.notify.DEFAULTS, {sound: false, position: "top center", delayIndicator: false});
    Lobibox.window.DEFAULTS = $.extend({}, Lobibox.window.DEFAULTS, {width: 700, height: "auto"});

    jaxon.command.handler.register("lobibox.show", jaxon.dialogs.lobibox.show);
    jaxon.command.handler.register("lobibox.hide", jaxon.dialogs.lobibox.hide);
    jaxon.command.handler.register("lobibox.notify", function(args) {
        Lobibox.notify(args.data.type, {title: args.data.title, msg: args.data.message});
    });

<?php if(($this->defaultForAlert)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.lobibox.success;
    jaxon.ajax.message.info = jaxon.dialogs.lobibox.info;
    jaxon.ajax.message.warning = jaxon.dialogs.lobibox.warning;
    jaxon.ajax.message.error = jaxon.dialogs.lobibox.error;
<?php endif ?>
<?php if(($this->defaultForConfirm)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.lobibox.confirm;
<?php endif ?>
});
