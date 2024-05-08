/**
 * Class: jaxon.dialog.lib.toastr
 */

jaxon.dialog.lib.register('toastr', (self) => {
    const xTypes = {
        success: 'success',
        info: 'info',
        warning: 'warning',
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
        const func = xTypes[type] ?? xTypes.info;
        toastr[func](text, title);
    };
});
