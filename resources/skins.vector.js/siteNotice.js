/**
 * Turns the close button of the site notice into a simple X button.
 */
function init() {
    const button = document.querySelector('.mw-dismissable-notice-close a')
    if ( button && button.textContent && button.previousSibling && button.nextSibling ) {
        button.setAttribute('title', button.textContent);
        button.textContent = '×';
        button.previousSibling.remove();
        button.nextSibling.remove();
    }
}

module.exports = init;
