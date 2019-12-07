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
