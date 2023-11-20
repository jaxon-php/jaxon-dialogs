jaxon.dialogs.pnotify = {
    alert: (data) => {
        notice = new PNotify(data);
        notice.get().click(notice.remove);
    },
    success: (content, title) => jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'success'}),
    info: (content, title) => jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'info'}),
    warning: (content, title) => jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'notice'}),
    error: (content, title) => jaxon.dialogs.pnotify.alert({text: content, title: title, type: 'error'}),
    confirm: (question, title, yesCallback, noCallback) => {
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
                labels: {}
            }
        });
        notice.get().on("pnotify.confirm", yesCallback);
        noCallback && notice.get().on("pnotify.cancel", noCallback);
    }
};
