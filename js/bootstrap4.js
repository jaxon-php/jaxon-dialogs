/*
 * Bootstrap 4 dialogs plugin
 * Class: jaxon.dialog.libs.bootstrap4
 */

jaxon.dom.ready(() => jaxon.dialog.register('bootstrap4', (self, options, utils) => {
    // Dialogs options
    const {
        modal: modalOptions = {},
    } = options;

    // Create the DOM element for the modal
    if(!utils.jq('#bootstrap4ModalContainer').length)
    {
        utils.jq('body').append('<div id="bootstrap4ModalContainer"></div>');
    }

    const modal = {
        container: document.getElementById('bootstrap4ModalContainer'),
        id: 'bootstrap4Modal',
    };

    const encodeHandler = (click) => JSON.stringify(click)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    const getHtml = (title, content, buttons) => {
        buttons = buttons
            .map(({ title, class: cssClass, click }) => {
                const attrs = click === 'close' ? 'data-dismiss="modal"' :
                    `jxn-click="${encodeHandler(click)}"`;
                return `<button type="button" class="${cssClass}" ${attrs}>${title}</button>`;
            })
            .reduce((html, button) => html + button, '');

        return `
<div id="${modal.id}" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">${title}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ${content}
            </div>
            <div class="modal-footer">
                ${buttons}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
`;
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
        modal.container.innerHTML = getHtml(title, content, buttons);
        // Pass the js content element to the callback.
        const element = utils.jq('#' + modal.id);
        element.on('shown.bs.modal', () => jsElement(modal.container));
        // Show the modal.
        element.modal({...modalOptions, ...options});
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => utils.jq(modal.id).modal('hide');
}));
