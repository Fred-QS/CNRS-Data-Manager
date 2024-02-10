/*********************************************************************************************
 *                                CNRS Data Manager Agents JS                                *
 ********************************************************************************************/

const viewSelector = document.querySelectorAll('.cnrs-dm-front-selector');

window.addEventListener('load', function(){
    for (let i = 0; i < viewSelector.length; i++) {
        viewSelector[i].addEventListener('click', function (){
            clearSelectors();
            this.classList.add('selected');
        });
    }
});

function clearSelectors() {
    for (let i = 0; i < viewSelector.length; i++) {
        viewSelector[i].classList.remove('selected');
    }
}