import Sortable from "sortablejs";

const menus = document.querySelectorAll('.js-menu');
let requestSent = false;

const toggleTypeFields = function (selected) {
    switch (selected) {
        case 1:
        default:
            $('.js-type-fields').hide();
            break;

        case 2:
            $('.js-type-fields:not([data-type="page"])').hide();
            $('.js-type-fields[data-type="page"]').show();
            break;

        case 3:
            $('.js-type-fields:not([data-type="tag"])').hide();
            $('.js-type-fields[data-type="tag"]').show();
            break;


        case -1:
            $('.js-type-fields:not([data-type="external"])').hide();
            $('.js-type-fields[data-type="external"]').show();
            break;
    }
};

const toggleTypesAllowedForParent = function (parent) {
    if (!parent) {
        $('[name="item[type]"]').prop('disabled', false);
    } else {
        $('[name="item[type]"]').each((idx, el) => {
            if (parseInt($(el).val()) === 1) {
                $(el).prop('disabled', true);

                if ($(el).is(':checked')) {
                    $('[name="item[type]"]:not(:disabled)').first().prop('checked', true);
                }

                $(el).prop('checked', false);
            }
        });

        let type = parseInt($('[name="item[type]"]:checked').val());
        toggleTypeFields(type);
    }
};

menus.forEach(el => {
    Sortable.create(el, {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,

        onMove: function (evt) {
            let currentType = $(evt.dragged).data('type');
            let targetContainerType = $(evt.related).closest('.js-container').data('type');

            if (currentType === 1 && targetContainerType === 'folder') {
                return false;
            }
        },

        store: {
            set: function () {
                if (requestSent) {
                    return;
                }
                requestSent = true;

                let items = $('.js-menu[data-type="root"]')
                    .find(".js-item")
                    .map((idx, el) => {
                        return {
                            id: $(el).attr('id'),
                            type: $(el).data('type'),
                            order: idx + 1,
                            parent: $(el).closest('.js-container').data('id') || null
                        };
                    })
                    .toArray();

                const formData = {};
                items.forEach((item, idx) => {
                    formData['order[items][' + idx + '][id]'] = item.id;
                    formData['order[items][' + idx + '][type]'] = item.type;
                    formData['order[items][' + idx + '][order]'] = item.order;
                    formData['order[items][' + idx + '][parent]'] = item.parent;
                });

                $('#loader').show();

                $.ajax({
                    method: 'POST',
                    url: '/admin/menu/reorder',
                    data: formData,
                    success: () => {
                        requestSent = false;
                    },
                    complete: () => {
                        $('#loader').hide();
                    }
                });
            }
        }
    })
});

const addMenuItem = function (callee) {
    let $this = $(callee);

    $('#loader').show();

    $.ajax({
        method: "GET",
        url: $this.attr('formaction'),
        success: (response) => {
            let $modal = $('.ui.tiny.modal');

            $modal.html(response);
            $modal.modal('show');
            $('.ui.radio.checkbox').checkbox();

            let value = $('#item_parent').val();
            toggleTypesAllowedForParent(value);
        },
        complete: () => {
            $('#loader').hide();
        }
    });
};

$(document).on('change', '#item_parent', function () {
    let value = $(this).val();

    toggleTypesAllowedForParent(value);
});

$(document).on('change', '[name="item[type]"]', function () {
    let value = parseInt($(this).val());

    toggleTypeFields(value);
});

$(function () {
    let type = parseInt($('[name="item[type]"]:checked').val());
    let parent = $('#item_parent').val();

    toggleTypeFields(type);
    toggleTypesAllowedForParent(parent);
});

window.addMenuItem = addMenuItem;
