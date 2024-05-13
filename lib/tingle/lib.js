/**
 * Class: jaxon.dialog.lib.tingle
 */

jaxon.dialog.lib.register('tingle', (self, { js, types }) => {
    /**
     * The modal window object
     *
     * @var {mixed}
     */
    self.modal = null;

    /**
     * Show the modal dialog
     *
     * @param {string} title The dialog title
     * @param {string} content The dialog HTML content
     * @param {array} buttons The dialog buttons
     * @param {array} options The dialog options
     *
     * @returns {void}
     */
    self.show = (title, content, buttons, options) => {
        self.hide();
        self.modal = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            ...options,
        });
        // Set content
        self.modal.setContent('<h2>' + title + '</h2>' + content);
        // Add buttons
        buttons.forEach(({ title, class: btnClass, click }) => {
            const handler = types.isObject(click) ?
                () => js.execCall(click) : () => self.hide();
            self.modal.addFooterBtn(title, btnClass, handler);
        });
        // Open the modal
        self.modal.open();
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        if(!self.modal)
        {
            return;
        }
        // Close an destroy the modal
        self.modal.close();
        self.modal.destroy();
        self.modal = null;
    };
});
