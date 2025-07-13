const steps = document.querySelectorAll('.step');
const prevBtn = document.querySelector('#prevBtn');
const nextBtn = document.querySelector('#nextBtn');
const indicators = document.querySelector('#indicators');
let currentStep = 0;

    function updateCarousel() {
        steps.forEach((step, idx) => {
            step.classList.toggle('hidden', idx !== currentStep);
            step.classList.toggle('active', idx === currentStep);
        });
        updateIndicators();
    }

    function updateIndicators() {
        indicators.innerHTML = '';
        steps.forEach((_, idx) => {
            const dot = document.createElement('span');
            dot.className = 'indicator' + (idx === currentStep ? ' active' : '');
            dot.addEventListener('click', () => {
                currentStep = idx;
                updateCarousel();
            });
            indicators.appendChild(dot);
        });
    }

    prevBtn.addEventListener('click', () => {
        currentStep = (currentStep - 1 + steps.length) % steps.length;
        updateCarousel();
    });

    nextBtn.addEventListener('click', () => {
        currentStep = (currentStep + 1) % steps.length;
        updateCarousel();
    });

    updateCarousel();
;
