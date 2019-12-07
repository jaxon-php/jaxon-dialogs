jaxon.dialogs.simplytoast = {
    success: function(content, title) {
        $.simplyToast(content, 'success', <?php echo $this->options ?>);
    },
    info: function(content, title) {
        $.simplyToast(content, 'info', <?php echo $this->options ?>);
    },
    warning: function(content, title) {
        $.simplyToast(content, 'warning', <?php echo $this->options ?>);
    },
    error: function(content, title) {
        $.simplyToast(content, 'danger', <?php echo $this->options ?>);
    }
};
