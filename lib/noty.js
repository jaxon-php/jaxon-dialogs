/**
 * Class: jaxon.dialog.lib.noty
 */

jaxon.dialog.lib.register('noty', (self, { labels }) => {
    const xTypes = {
        success: 'success',
        info: 'information',
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
        new Noty({
            text,
            type: xTypes[type] ?? xTypes.info,
            layout: 'topCenter',
            timeout: 5000,
        }).show();
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
        const noty = new Noty({
            theme: 'relax',
            text: question,
            layout: 'topCenter',
            buttons: [
                Noty.button(labels.yes, 'btn btn-success', () => {
                    noty.close();
                    yesCallback();
                }, {'data-status': 'ok'}),
                Noty.button(labels.no, 'btn btn-error', () => {
                    noty.close();
                    noCallback && noCallback();
                }),
            ],
        });
        noty.show();
    };
});
