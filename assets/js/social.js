(function () {
    const shareButtons = document.querySelectorAll('.share-btn');

    shareButtons.forEach(function (element) {
        element.onclick = function (event) {
            let target = event.currentTarget,
                href = target.href,
                title = target.title;

            window.open(href, title, "width=600,height=400");

            return false;
        };
    });
})();