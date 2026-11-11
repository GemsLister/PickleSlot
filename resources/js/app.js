import './booking-page/booking-page.js';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.querySelectorAll('[data-carousel]').forEach(carousel => {
    const previousButton = document.querySelector(`[aria-controls="${carousel.id}"][data-carousel-prev]`);
    const nextButton = document.querySelector(`[aria-controls="${carousel.id}"][data-carousel-next]`);

    if (!previousButton || !nextButton) {
        return;
    }

    function updateCarouselButtons() {
        previousButton.disabled = carousel.scrollLeft <= 1;
        nextButton.disabled = carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 1;
    }

    function scrollCarousel(direction) {
        const firstCard = carousel.querySelector(':scope > [data-reveal]');

        if (!firstCard) {
            return;
        }

        const gap = Number.parseFloat(window.getComputedStyle(carousel).columnGap) || 0;
        const distance = firstCard.getBoundingClientRect().width + gap;

        carousel.scrollBy({
            left: direction * distance,
            behavior: prefersReducedMotion ? 'auto' : 'smooth',
        });
    }

    previousButton.addEventListener('click', () => scrollCarousel(-1));
    nextButton.addEventListener('click', () => scrollCarousel(1));
    carousel.addEventListener('scroll', updateCarouselButtons, { passive: true });
    window.addEventListener('resize', updateCarouselButtons);
    updateCarouselButtons();
});

const revealElements = document.querySelectorAll('[data-reveal]');

if (revealElements.length > 0 && !prefersReducedMotion && 'IntersectionObserver' in window) {
    document.documentElement.classList.add('motion-ready');

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealElements.forEach(element => revealObserver.observe(element));
}
