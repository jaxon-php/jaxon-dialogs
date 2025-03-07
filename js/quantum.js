/**
 * Class: jaxon.dialog.libs.quantum
 */

jaxon.dom.ready(() => jaxon.dialog.register('quantum', (self, options, utils) => {
    // Dialogs options
    const {
        labels,
    } = options;

    const xTypes = {
        success: 'success',
        info: 'info',
        warning: 'warning',
        error: 'error',
    };

    jaxon.dialog.quantum = {};

    /**
     * Show an alert message
     *
     * @param {object} alert The alert parameters
     * @param {string} alert.type The alert type
     * @param {string} alert.title The alert title
     * @param {string} alert.message The alert message
     *
     * @returns {void}
     */
    self.alert = ({ type, title, message }) => {
        type = xTypes[type] ?? xTypes.info;
        Qual[type](title, message);
    };

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
    self.confirm = ({ question, title }, { yes: yesCb, no: noCb = () => {} }) => {
        // Create functions with random names for callbacks.
        const sCbName = utils.createUniqueId(16);
        // Make the callbacks globally accessible.
        jaxon.dialog.quantum[sCbName] = {
            yes: () => {
                // Remove after calling.
                delete jaxon.dialog.quantum[sCbName];
                close_qual(); // Close the confirm dialog.
                // The close_qual() function closes the dialog after a 250ms timeout.
                // We set a 300ms timeout to make sure the callback is called after.
                setTimeout(() => yesCb(), 300);
            },
            no: () => {
                // Remove after calling.
                delete jaxon.dialog.quantum[sCbName];
                close_qual(); // Close the confirm dialog.
                // The close_qual() function closes the dialog after a 250ms timeout.
                // We set a 300ms timeout to make sure the callback is called after.
                setTimeout(() => noCb(), 300);
            },
        };
        Qual.confirm(title, question, succ, labels.yes, labels.no,
            `jaxon.dialog.quantum.${sCbName}.yes`, `jaxon.dialog.quantum.${sCbName}.no`);
    };
}));
