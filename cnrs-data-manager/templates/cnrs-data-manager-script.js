/*********************************************************************************************
 *                                CNRS Data Manager Agents JS                                *
 ********************************************************************************************/

const viewSelector = document.querySelectorAll('.cnrs-dm-front-selector');
const allItems = document.querySelectorAll('.cnrs-dm-front-agent-container');
const listItems = document.querySelectorAll('.cnrs-dm-front-inline');
const gridItems = document.querySelectorAll('.cnrs-dm-front-card');
const allRows = document.querySelectorAll('.cnrs-dm-front-agent-wrapper');
const infoModals = document.querySelectorAll('.cnrs-dm-front-agent-info-wrapper');
const dataWrapper = document.querySelectorAll('.cnrs-dm-front-agent-info-data-container');
const parentContainers = document.querySelectorAll('.cnrs-dm-front-container');

window.addEventListener('load', function(){
    displayCOntainers();
    isMobile();
    for (let i = 0; i < viewSelector.length; i++) {
        viewSelector[i].addEventListener('click', function (){
            const action = this.dataset.action;
            clearSelectors();
            this.classList.add('selected');
            if (action === 'list') {
                for (let i = 0; i < listItems.length; i++) {
                    listItems[i].classList.add('selected');
                }
                for (let i = 0; i < allRows.length; i++) {
                    allRows[i].classList.add('cnrs-dm-front-agent-wrapper-list');
                    allRows[i].classList.remove('cnrs-dm-front-agent-wrapper-grid');
                }
            } else {
                for (let i = 0; i < gridItems.length; i++) {
                    gridItems[i].classList.add('selected');
                }
                for (let i = 0; i < allRows.length; i++) {
                    allRows[i].classList.remove('cnrs-dm-front-agent-wrapper-list');
                    allRows[i].classList.add('cnrs-dm-front-agent-wrapper-grid');
                }
            }
        });
    }
    dispatchWrapperListener();
});


function dispatchWrapperListener() {
    for (let i = 0; i < allRows.length; i++) {
        allRows[i].onclick = function (){
            let element = this.querySelector('.cnrs-dm-front-agent-info-wrapper').cloneNode(true);
            document.body.appendChild(element);
            setTimeout(function (){
                document.querySelector('body > .cnrs-dm-front-agent-info-wrapper').classList.add('show');
                document.querySelector('body > .cnrs-dm-front-agent-info-wrapper .cnrs-dm-close-info-container').onclick = function () {
                    document.querySelector('body > .cnrs-dm-front-agent-info-wrapper').classList.remove('show');
                    setTimeout(function(){
                        document.querySelector('body > .cnrs-dm-front-agent-info-wrapper').remove();
                    }, 550);
                }
            }, 50);
        };
    }
}
function clearSelectors() {
    for (let i = 0; i < viewSelector.length; i++) {
        viewSelector[i].classList.remove('selected');
    }
    for (let i = 0; i < allItems.length; i++) {
        allItems[i].classList.remove('selected');
    }
}

function isMobile() {
    const regex = /Mobi|Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
    if (regex.test(navigator.userAgent)) {
        for (let i = 0; i < dataWrapper.length; i++) {
            dataWrapper[i].classList.add('mobile-version');
        }
    }
}

function displayCOntainers() {
    for (let i = 0; i < parentContainers.length; i++) {
        parentContainers[i].style.display = 'block';
    }
}