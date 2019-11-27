jaxon.dialogs.izi = {
    success: function(content, title) {
        if(title == undefined) title = 'Success';
        iziToast.success({message: content, title: title, position: "topCenter", close: true});
    },
    info: function(content, title) {
        if(title == undefined) title = 'Information';
        iziToast.info({message: content, title: title, position: "topCenter", close: true});
    },
    warning: function(content, title) {
        if(title == undefined) title = 'Warning';
        iziToast.warning({message: content, title: title, position: "topCenter", close: true});
    },
    error: function(content, title) {
        if(title == undefined) title = 'Error';
        iziToast.error({message: content, title: title, position: "topCenter", close: true});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        iziToast.show({
            close: false,
            layout: 2,
            icon: "icon-person",
            position: "center",
            timeout: 0,
            title: title,
            message: question,
            buttons: [
                ["<button><?php echo $this->yes ?></button>", function (instance, toast) {
                    instance.hide({transitionOut: "fadeOutUp"}, toast);
                    yesCallback();
                }],
                ["<button><?php echo $this->no ?></button>", function (instance, toast) {
                    instance.hide({transitionOut: "fadeOutUp"}, toast);
                    if(noCallback != undefined)
                        noCallback();
                }]
            ],
        });
    }
};

jaxon.dom.ready(function() {
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

<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.izi.success;
    jaxon.ajax.message.info = jaxon.dialogs.izi.info;
    jaxon.ajax.message.warning = jaxon.dialogs.izi.warning;
    jaxon.ajax.message.error = jaxon.dialogs.izi.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.izi.confirm;
<?php endif ?>
});
