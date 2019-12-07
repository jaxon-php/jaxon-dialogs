jaxon.dialogs.notify = {
    success: function(content, title) {
        $.notify(content, {className: 'success', position: "top center"});
    },
    info: function(content, title) {
        $.notify(content, {className: 'info', position: "top center"});
    },
    warning: function(content, title) {
        $.notify(content, {className: 'warn', position: "top center"});
    },
    error: function(content, title) {
        $.notify(content, {className: 'error', position: "top center"});
    }
};
