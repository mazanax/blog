require('../css/semantic.css');
require('../css/admin.css');
require('../js/semantic.js');

global.$ = global.jQuery = require('jquery');

const fontsContext = require.context('../fonts', true, /\.(ttf|woff|woff2)$/);
fontsContext.keys().forEach(fontsContext);

$(document).on('submit', 'form', () => {
    $('#loader').show();
});

$(function () {
    let menuItems = $('#main-menu a.item');
    let currentPath = window.location.pathname;
    let pathParts = currentPath.split('/');
    let selected = false;

    while (pathParts && !selected) {
        menuItems.each((_, el) => {
            let $item = $(el);

            if (0 === $item.attr('href').indexOf(pathParts.join('/')) && !selected) {
                $item.addClass('active');
                selected = true;
            }
        });

        pathParts.pop();
    }
});