/**
 * Class: jaxon.dialog.lib.cute
 */

jaxon.dialog.lib.register('cute', (self, { labels, options = {} }) => {
    // Dialogs options
    const {
        alert: alertOptions = {},
        confirm: confirmOptions = {},
    } = options;

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
        cuteAlert({
            ...alertOptions,
            message: text,
            title: title ?? '&nbsp;',
            type: xTypes[type] ?? xTypes.info,
        });
    };

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => {
        cuteAlert({
            ...confirmOptions,
            title,
            type: 'question',
            message: question,
            confirmText: labels.yes,
            cancelText: labels.no,
        }).then(e => {
            if(e === 'confirm')
            {
                yesCallback();
            }
            else if((noCallback))
            {
                noCallback();
            }
        });
    };
});
