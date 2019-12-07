import "tags/build/css/style.css";
import Tags from 'tags/build/js/script';

const tagsElements = Array.from(document.querySelectorAll('[name^="post[tags]"]')),
    tagsMap = !tagsElements.length ? {}
        : Object.assign.apply({},
            Array.from(document.querySelectorAll('[name^="post[tags]"]'))
                .map((el) => {
                    let key = el.value,
                        idx = parseInt(el.getAttribute('id').replace('post_tags_', ''));

                    return {[key]: idx};
                })
        );

const element = document.getElementById('post-tags'),
    container = document.getElementById('post_tags'),
    template = container.getAttribute('data-prototype');
let idxIterator = Math.max(0, ...Object.values(tagsMap)) + 1;

new Tags(element, {
    data: Object.keys(tagsMap),
    onTagAdded: function (addedTag) {
        tagsMap[addedTag] = idxIterator++;
        container.insertAdjacentHTML('beforeend', template.replace(/__name__/gi, tagsMap[addedTag]));
        document.getElementById('post_tags_' + tagsMap[addedTag]).value = addedTag;

        console.log('Added: ' + addedTag, tagsMap);
    },
    onTagDeleted: function (deletedTag) {
        document.getElementById('post_tags_' + tagsMap[deletedTag]).remove();
        delete tagsMap[deletedTag];

        console.log('Deleted: ' + deletedTag, tagsMap);
    }
});

document.querySelector('#post-tags input')
    .setAttribute('list', 'allowed-tags');