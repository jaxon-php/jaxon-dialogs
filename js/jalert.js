/**
 * Class: jaxon.dialog.lib.jalert
 */

jaxon.dialog.lib.register('jalert', (self, { labels, options = {} }) => {
    // Dialogs options
    const {
        alert: alertOptions = {},
        confirm: confirmOptions = {},
    } = options;

    const xTypes = {
        success: 'green',
        info: 'blue',
        warning: 'yellow',
        error: 'red',
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
    self.alert = (type, text, title) => $.jAlert({
        ...alertOptions,
        content: text,
        title,
        theme: xTypes[type] ?? xTypes.info,
    });

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => $.jAlert({
        ...confirmOptions,
        title,
        type: "confirm",
        confirmQuestion: question,
        confirmBtnText: labels.yes,
        denyBtnText: labels.no,
        onConfirm: yesCallback,
        onDeny: noCallback ?? (() => {}),
    });
});
