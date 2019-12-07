const $ = require('jquery');

$(document).on("change", "#checkbox", function () {
    document.querySelector('#list-config').submit();
});