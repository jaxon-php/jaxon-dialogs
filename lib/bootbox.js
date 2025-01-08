/**
 * Bootbox dialogs plugin
 * Class: jaxon.dialog.lib.bootbox
 */

jaxon.dialog.lib.register('bootbox', (self, { dom, js, types, jq, labels }) => {
    /**
     * @var {object}
     */
    const dialog = {
        dom: null,
        container: 'bootbox-container',
    };

    // Append the dialog container to the page HTML code.
    dom.ready(() => {
        if(!jq('#' + dialog.container).length)
        {
            jq('body').append('<div id="' + dialog.container + '"></div>');
        }
    });

    const dialogHtml = (title, content, buttons) => {
        return `
    <div id="styledModal" class="modal modal-styled">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">${title}</h3>
                </div>
                <div class="modal-body">
${content}
                </div>
                <div class="modal-footer">` +
            buttons.map(({ title, class: btnClass, click }, btnIndex) => {
                return types.isObject(click) ?
`
                    <button type="button" class="${btnClass}" id="bootbox-dlg-btn${btnIndex}">${title}</button>` :
`
                    <button type="button" class="${btnClass}" data-dismiss="modal">${title}</button>`;
            }).reduce((sButtons, sButton) => sButtons + sButton, '') +
`
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->`;
    };

    /**
     * Show the modal dialog
     *
     * @param {string} title The dialog title
     * @param {string} content The dialog HTML content
     * @param {array} buttons The dialog buttons
     * @param {array} options The dialog options
     * @param {int} options.width The dialog options
     * @param {function} jsElement A callback to call with the dialog js content element
     *
     * @returns {object}
     */
    self.show = (title, content, buttons, { width }, jsElement) => {
        jq('#' + dialog.container).html(dialogHtml(title, content, buttons));
        dialog.dom = jq('#styledModal');
        dialog.dom.modal('show');
        width && jq('.modal-dialog').css('width', `${width}px`);
        // Set the buttons onclick handlers
        buttons.forEach(({ click }, btnIndex) => {
            types.isObject(click) &&
                jq(`#bootbox-dlg-btn${btnIndex}`).click(() => js.execExpr(click));
        });
        // Pass the js content element to the callback.
        jsElement(dialog.dom.get(0));
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => {
        if ((dialog.dom)) {
            dialog.dom.modal('hide');
            dialog.dom = null;
        }
    };

    const xTypes = {
        success: 'success',
        info: 'info',
        warning: 'warning',
        error: 'danger',
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
        const html = '<div class="alert alert-' + (xTypes[type] ?? xTypes.info) +
            '" style="margin-top:15px;margin-bottom:-15px;">' +
            (!title ? '' : '<strong>' + title + '</strong><br/>') + text + '</div>';
        bootbox.alert(html);
    };

    /**
     * @param {string} question The question to ask
     * @param {string} title The question title
     * @param {callback} yesCallback The function to call if the answer is yes
     * @param {callback} noCallback The function to call if the answer is no
     *
     * @returns {void}
     */
    self.confirm = (question, title, yesCallback, noCallback) => bootbox.confirm({
        title: title,
        message: question,
        buttons: {
            cancel: {label: labels.no},
            confirm: {label: labels.yes}
        },
        callback: (res) => {
            if(res)
                yesCallback();
            else if((noCallback))
                noCallback();
        }
    });
});
