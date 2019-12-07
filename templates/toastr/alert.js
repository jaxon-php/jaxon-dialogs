jaxon.dialogs.toastr = {
    success: function(content, title) {
        if((title))
            toastr.success(content, title);
        else
            toastr.success(content);
    },
    info: function(content, title) {
        if((title))
            toastr.info(content, title);
        else
            toastr.info(content);
    },
    warning: function(content, title) {
        if((title))
            toastr.warning(content, title);
        else
            toastr.warning(content);
    },
    error: function(content, title) {
        if((title))
            toastr.error(content, title);
        else
            toastr.error(content);
    }
};
