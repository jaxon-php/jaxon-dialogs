/**
 * Class: jaxon.dialog.libs.notify
 */

jaxon.dom.ready(() => jaxon.dialog.register('notify', (self, options) => {
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
     * @param {object} alert The alert parameters
     * @param {string} alert.type The alert type
     * @param {string} alert.message The alert message
     *
     * @returns {void}
     */
    self.alert = ({ type, message }) => {
        $.notify(message, {
            ...alertOptions,
            className: xTypes[type] ?? xTypes.info,
            position: "top center",
        });
    };
}));
