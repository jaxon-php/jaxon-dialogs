/**
 * Class: jaxon.dialog.libs.tingle
 */

jaxon.dom.ready(() => jaxon.dialog.register('tingle', (self, options, utils) => {
    // Dialogs options
    const {
        modal: modalOptions = {},
    } = options;

    /**
     * @var {object}
     */
    const dialog = {
        dom: null,
    };

    /**
     * Show the modal dialog
     *
     * @param {object} dialog The dialog parameters
     * @param {string} dialog.title The dialog title
     * @param {string} dialog.content The dialog HTML content
     * @param {array} dialog.buttons The dialog buttons
     * @param {array} dialog.options The dialog options
     * @param {function} jsElement A callback to call with the dialog js content element
     *
     * @returns {object}
     */
    self.show = ({ title, content, buttons, options }, jsElement) => {
        self.hide();
        dialog.dom = new tingle.modal({
            ...modalOptions,
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            ...options,
        });
        // Set content
        dialog.dom.setContent('<h2>' + title + '</h2>' + content);
        // Add buttons
        buttons.forEach(({ title, class: btnClass, click }) => {
            const handler = utils.isObject(click) ?
                () => utils.js(click) : () => self.hide();
            dialog.dom.addFooterBtn(title, btnClass, handler);
        });
        // Open the modal
        dialog.dom.open();
        // Pass the js content element to the callback.
        jsElement(dialog.dom.modalBox);
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        if(!dialog.dom)
        {
            return;
        }
        // Close an destroy the modal
        dialog.dom.close();
        dialog.dom.destroy();
        dialog.dom = null;
    };
}));
