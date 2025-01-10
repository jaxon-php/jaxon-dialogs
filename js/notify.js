/**
 * Class: jaxon.dialog.lib.notify
 */

jaxon.dialog.lib.register('notify', (self, { options = {} }) => {
    // Dialogs options
    const {
        alert: alertOptions = {},
    } = options;

    const xTypes = {
        success: 'success',
        info: 'info',
        warning: 'warn',
        error: 'error',
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
    self.alert = (type, text, title) => {
        $.notify(text, {
            ...alertOptions,
            className: xTypes[type] ?? xTypes.info,
            position: "top center",
        });
    };
});
