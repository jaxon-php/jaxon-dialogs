jaxon.dialogs.swal = {
    options: {
        allowEscapeKey: true,
        allowOutsideClick: true
    },
    success: function(content, title) {
        if(title == undefined) title = 'Success';
        swal({text: content, title: title, type: 'success'});
    },
    info: function(content, title) {
        if(title == undefined) title = 'Information';
        swal({text: content, title: title, type: 'info'});
    },
    warning: function(content, title) {
        if(title == undefined) title = 'Warning';
        swal({text: content, title: title, type: 'warning'});
    },
    error: function(content, title) {
        if(title == undefined) title = 'Error';
        swal({text: content, title: title, type: 'error'});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        swal({
            type: "warning",
            title: title,
            confirmButtonText: "<?php echo $this->yes ?>",
            cancelButtonText: "<?php echo $this->no ?>",
            showCancelButton: true,
            text: question
        },
        function(res){
            if(res)
                yesCallback();
            else if(noCallback != undefined)
                noCallback();
        });
    }
};
