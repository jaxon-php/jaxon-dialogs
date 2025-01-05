/*
 * Bootstrap dialogs plugin
 * Class: jaxon.dialog.lib.bootstrap
 */

jaxon.dialog.lib.register('bootstrap', (self, { js, types, labels }) => {
    /**
     * Show the modal dialog
     *
     * @param {string} title The dialog title
     * @param {string} content The dialog HTML content
     * @param {array} buttons The dialog buttons
     * @param {array} options The dialog options
     * @param {function} jsElement A callback to call with the dialog js content element
     *
     * @returns {object}
     */
    self.show = (title, content, buttons, options, jsElement) => {
        dialog = BootstrapDialog.show({
            title,
            message: content,
            buttons: buttons.map(({ title: label, class: cssClass, click }) => {
                const handler = types.isObject(click) ?
                    () => js.execExpr(click) : dialog => dialog.close();
                return {
                    label,
                    cssClass,
                    action: handler,
                };
            }),
        });
        // Pass the js content element to the callback.
        jsElement(dialog.$modal.get(0));
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => BootstrapDialog.closeAll();

    const xTypes = {
        success: BootstrapDialog.TYPE_SUCCESS,
        info: BootstrapDialog.TYPE_INFO,
        warning: BootstrapDialog.TYPE_WARNING,
        danger: BootstrapDialog.TYPE_DANGER
    };

    /**
     * Show an alert message
     *
     * @param {string} type The message type
     * @param {string} text The message text
     * @param {string} title The message title
     *
     * @returns {void}
     */
    self.alert = (type, text, title) =>
        BootstrapDialog.alert({ title, type: xTypes[type] ?? xTypes.info, message: text });

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => BootstrapDialog.confirm({
        title,
        message: question,
        btnOKLabel: labels.yes,
        btnCancelLabel: labels.no,
        callback: (res) => {
            if(res)
                yesCallback();
            else if(noCallback !== undefined)
                noCallback();
        }
    });
});
