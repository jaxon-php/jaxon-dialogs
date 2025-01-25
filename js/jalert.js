/**
 * Class: jaxon.dialog.libs.jalert
 */

jaxon.dom.ready(() => jaxon.dialog.register('jalert', (self, options) => {
    // Dialogs options
    const {
        labels,
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
     * @param {object} alert The alert parameters
     * @param {string} alert.type The alert type
     * @param {string} alert.message The alert message
     * @param {string} alert.title The alert title
     *
     * @returns {void}
     */
    self.alert = ({ type, message, title }) => $.jAlert({
        ...alertOptions,
        content: message,
        title,
        theme: xTypes[type] ?? xTypes.info,
    });

    /**
     * Ask a confirm question to the user.
     *
     * @param {object} confirm The confirm parameters
     * @param {string} confirm.question The question to ask
     * @param {string} confirm.title The question title
     * @param {object} callback The confirm callbacks
     * @param {callback} callback.yes The function to call if the answer is yes
     * @param {callback=} callback.no The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = ({ question, title}, { yes: yesCb, no: noCb = () => {} }) => $.jAlert({
        ...confirmOptions,
        title,
        type: "confirm",
        confirmQuestion: question,
        confirmBtnText: labels.yes,
        denyBtnText: labels.no,
        onConfirm: yesCb,
        onDeny: noCb,
    });
}));
