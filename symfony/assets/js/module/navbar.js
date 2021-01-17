window.onload=function(){
    const app = (() => {
        let body;
        let menu;
        let menuItems;

        const init = () => {
            body = document.querySelector('body');
            menu = document.querySelector('.menu-icon');
            menuItems = document.querySelectorAll('.nav__list-item');
            applyListeners();
            // applyMenuListeners();
        }

        const applyListeners = () => {
            menu.addEventListener('click', () => toggleClass(body, 'nav-active'));
        }

        // const applyMenuListeners = () => {
        //     document.getElementById('home').addEventListener('click', () => toggleClass(body, 'nav-active'));
        // }

        const toggleClass = (element, stringClass) => {
            if(element.classList.contains(stringClass))
                element.classList.remove(stringClass);
            else
                element.classList.add(stringClass);
        }

        init();
    })();
}

