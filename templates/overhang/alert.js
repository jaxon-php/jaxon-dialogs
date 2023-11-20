jaxon.dialogs.overhang = {
    success: (content, title) => $("body").overhang({message: content, title: title, type: 'success', duration: 5}),
    info: (content, title) => $("body").overhang({message: content, title: title, type: 'info', duration: 5}),
    warning: (content, title) => $("body").overhang({message: content, title: title, type: 'warn', duration: 5}),
    error: (content, title) => $("body").overhang({message: content, title: title, type: 'error', duration: 5}),
    confirm: (question, title, yesCallback, noCallback) => $("body").overhang({
        type: "confirm",
        message: question,
        callback: (res) => {
            if(res)
                yesCallback();
            else if(noCallback != undefined)
                noCallback();
        }
    }),
};
