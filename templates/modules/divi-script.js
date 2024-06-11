window.onload = function() {
    let main = document.querySelector('#page-container');
    let categoriesStyleTag = document.querySelector('#custom-categories-styles');
    if (main) {
        main.classList.add('et-animated-content');
        main.style.marginTop = '-26px';
        main.style.paddingTop = '112px';
        main.style.overflowY = 'hidden';
    }
    if (categoriesStyleTag) {
        let tag = document.createElement('style');
        tag.innerHTML = categoriesStyleTag.innerHTML;
        document.querySelector('head').appendChild(tag);
        categoriesStyleTag.remove();
        tag.setAttribute('id', 'custom-categories-styles');
    }
    document.querySelector('#custom-category-script').remove();
};