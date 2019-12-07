jaxon.dialogs.pnotify = {
    alert: function(data) {
        notice = new PNotify(data);
        notice.get().click(function(){notice.remove();});
    },
    success: function(content, title) {
        jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'success'});
    },
    info: function(content, title) {
        jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'info'});
    },
    warning: function(content, title) {
        jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'notice'});
    },
    error: function(content, title) {
        jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'error'});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        PNotify.prototype.options.confirm.buttons[0].text = "<?php echo $this->yes ?>";
        PNotify.prototype.options.confirm.buttons[1].text = "<?php echo $this->no ?>";
        notice = new PNotify({
            title: title,
            text: question,
            hide: false,
            confirm:{
                confirm: true
            },
            buttons:{
                closer: false,
                sticker: false,
                labels: {

                }
            }
        });
        notice.get().on("pnotify.confirm", yesCallback);
        if(noCallback != undefined)
            notice.get().on("pnotify.cancel", noCallback);
    }
};
