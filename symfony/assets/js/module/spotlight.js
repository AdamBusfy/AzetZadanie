import '../../css/page/spotlight.scss';


//DOM load event
window.addEventListener("DOMContentLoaded", () => {

    const spotlight = document.querySelector('.spotlight');

    let spotlightSize = 'transparent 300px, rgba(0, 0, 0, 0.85) 350px)';

    window.addEventListener('mousemove', e => updateSpotlight(e));

    function updateSpotlight(e) {

        spotlight.style.backgroundImage = `radial-gradient(circle at ${e.pageX / window.innerWidth * 100}% ${e.pageY / window.innerHeight * 100}%, ${spotlightSize}`;

    }
});