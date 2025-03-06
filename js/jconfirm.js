/**
 * Class: jaxon.dialog.libs.jconfirm
 */

jaxon.dom.ready(() => jaxon.dialog.register('jconfirm', (self, options, utils) => {
    // Dialogs options
    const {
        labels,
        modal: modalOptions = {},
        alert: alertOptions = {},
        confirm: confirmOptions = {},
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

        // Add buttons
        const xButtons = {};
        buttons.forEach(({ title: text, class: btnClass, click }, btnIndex) => {
            const handler = !utils.isObject(click) ? () => self.hide() :
                () => { utils.js(click); return false; };
            xButtons['btn' + btnIndex] = { text, btnClass, action: handler };
        });

        dialog.dom = $.confirm({
            ...modalOptions,
            title,
            content,
            ...options,
            closeIcon: true,
            useBootstrap: true,
            boxWidth: 600,
            buttons: xButtons,
            // Pass the js content element to the callback.
            onContentReady: () => jsElement(dialog.dom.$jconfirmBox.get(0)),
        });
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        !dialog.dom || dialog.dom.close();
        dialog.dom = null;
    };

    const xTypes = {
        success: 'green',
        info: 'blue',
        warning: 'orange',
        error: 'red',
    };

    const xIcons = {
        success: 'success',
        info: 'info',
        warning: 'warning',
        error: 'error',
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
    self.alert = ({ type, message, title }) => $.alert({
        ...alertOptions,
        content: message,
        title,
        type: xTypes[type] ?? xTypes.info,
        icon: 'fa fa-' + (xIcons[type] ?? xIcons.info),
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
    self.confirm = ({ question, title }, { yes: yesCb, no: noCb = () => {} }) => $.confirm({
        ...confirmOptions,
        title,
        content: question,
        buttons: {
            yes: {
                btnClass: "btn-blue",
                text: labels.yes,
                action: yesCb,
            },
            no: {
                text: labels.no,
                action: noCb,
            },
        },
    });
}));
