    // Modal container
    if(!$("#<?php echo $this->container ?>").length)
    {
        $('body').append('<div id="<?php echo $this->container ?>"></div>');
    }
    jaxon.command.handler.register("izimodal.show", function(args) {
        $("#<?php echo $this->container ?>").iziModal(args.data);
    });
