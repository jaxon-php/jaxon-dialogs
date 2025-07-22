/*
 * Bootstrap 5 dialogs plugin
 * Class: jaxon.dialog.libs.bootstrap5
 */

jaxon.dom.ready(() => jaxon.dialog.register('bootstrap5', (self, options, utils) => {
    // Dialogs options
    const {
        modal: modalOptions = {},
    } = options;

    // Create the DOM element for the modal
    if(!utils.jq('#bootstrap5ModalContainer').length)
    {
        utils.jq('body').append('<div id="bootstrap5ModalContainer"></div>');
    }

    const modal = {
        container: document.getElementById('bootstrap5ModalContainer'),
        id: 'bootstrap5Modal',
    };

    const encodeHandler = (click) => JSON.stringify(click)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    const getHtml = (title, content, buttons) => {
        buttons = buttons
            .map(({ title, class: cssClass, click }) => {
                const attrs = click === 'close' ? 'data-bs-dismiss="modal"' :
                    `jxn-click="${encodeHandler(click)}"`;
                return `<button type="button" class="${cssClass}" ${attrs}>${title}</button>`;
            })
            .reduce((html, button) => html + button, '');

        return `
<div id="${modal.id}" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">${title}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
     * @param {function} cbDomElement A callback to call with the DOM element of the dialog content
     *
     * @returns {object}
     */
    self.show = ({ title, content, buttons, options }, cbDomElement) => {
        modal.container.innerHTML = getHtml(title, content, buttons);
        const element = document.getElementById(modal.id);
        modal.instance = new bootstrap.Modal(element, {...modalOptions, ...options});
        // Pass the js content element to the callback.
        element.addEventListener('shown.bs.modal', () => cbDomElement(modal.container));
        // Show the modal.
        modal.instance.show();
    };

    /**
     * Hide the modal dialog
     *
     * @returns {void}
     */
    self.hide = () => modal.instance?.hide();
}));
