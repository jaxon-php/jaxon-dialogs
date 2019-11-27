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

jaxon.dom.ready(function() {
<?php echo $this->options ?>

    jaxon.command.handler.register("sweetalert.alert", function(args) {
        // Set user and default options into data only when they are missing
        for(key in jaxon.dialogs.swal.options)
        {
            if(!(key in args.data))
            {
                args.data[key] = jaxon.dialogs.swal.options[key];
            }
        }
        swal(args.data);
    });

<?php if(($this->defaultForMessage)): ?>
    jaxon.ajax.message.success = jaxon.dialogs.swal.success;
    jaxon.ajax.message.info = jaxon.dialogs.swal.info;
    jaxon.ajax.message.warning = jaxon.dialogs.swal.warning;
    jaxon.ajax.message.error = jaxon.dialogs.swal.error;
<?php endif ?>
<?php if(($this->defaultForQuestion)): ?>
    jaxon.ajax.message.confirm = jaxon.dialogs.swal.confirm;
<?php endif ?>
});
