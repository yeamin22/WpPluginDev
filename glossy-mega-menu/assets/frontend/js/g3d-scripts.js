function adjustImagePosition(cards, delta) {
    const cardHeight = cards[0].clientHeight; // Assuming all cards are the same height
    const totalHeight = cardHeight * cards.length;

    cards.forEach(card => {
        const style = window.getComputedStyle(card);
        const transform = style.transform === 'none' ? '' : style.transform;
        const matrix = transform.match(/matrix.*\((.+)\)/);
        
        let y = 0;
        if (matrix) {
            const values = matrix[1].split(', ');
            y = parseFloat(values[values.length === 16 ? 13 : 5]);
        }

        let newY = y + delta;
        
        if (delta > 0 && newY > totalHeight) {
            newY -= totalHeight;
        } else if (delta < 0 && newY < -totalHeight) {
            newY += totalHeight;
        }

        card.style.transform = `translate3d(0, ${newY}px, 0)`;
    });
}

let isScrollingAllowed = false;

// Setting up an IntersectionObserver to monitor entering and leaving the scrollable section
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        isScrollingAllowed = entry.isIntersecting;
    });
}, { threshold: 0.5 }); // Trigger when 50% of the section is visible

// Select the section to observe
const section = document.querySelector('.vertical-scroll-slider');
observer.observe(section);

window.addEventListener('scroll', function(event) {  
    if (!isScrollingAllowed) return;
    const deltaY = event.deltaY > 0 ? 150 : -150;
    var layers = document.querySelectorAll('.scroll-up .integration-logo-card');
    var layers_down = document.querySelectorAll('.scroll-down .integration-logo-card');
    adjustImagePosition(layers, -deltaY);
    adjustImagePosition(layers_down, deltaY);
  
}, { passive: false });


document.addEventListener("DOMContentLoaded",function(){
    var layers = document.querySelectorAll('.scroll-up');
    var layers_down = document.querySelectorAll('.scroll-down');
    initialTransformUp(layers);
    initialTransformDown(layers_down); 

});

function initialTransformUp(layers){
    layers.forEach(card => {
        card.style.transform = `translate3d(0, -400px, 0) rotate(-12deg)`;
    });
}

function initialTransformDown(layers){
    layers.forEach(card => {
        card.style.transform = `translate3d(0, -400px, 0) rotate(-12deg)`;
    });
}

