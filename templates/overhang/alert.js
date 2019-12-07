jaxon.dialogs.overhang = {
    success: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'success', duration: 5});
    },
    info: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'info', duration: 5});
    },
    warning: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'warn', duration: 5});
    },
    error: function(content, title) {
        $("body").overhang({message: content, title: title, type: 'error', duration: 5});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        $("body").overhang({
            type: "confirm",
            message: question,
            callback: function(res){
                if(res)
                    yesCallback();
                else if(noCallback != undefined)
                    noCallback();
            }
        });
    }
};
