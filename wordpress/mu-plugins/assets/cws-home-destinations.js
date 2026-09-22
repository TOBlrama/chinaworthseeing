(() => {
  'use strict';

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  document.querySelectorAll('.cws-destinations').forEach((section) => {
    const trackBlock = section.querySelector('.cws-destinations__track');
    const track = trackBlock?.querySelector(':scope > .wp-block-group__inner-container');

    if (!track) {
      return;
    }

    track.tabIndex = 0;
    track.setAttribute('role', 'region');
    track.setAttribute('aria-label', 'Drag or swipe to explore China destinations');

    track.querySelectorAll('img').forEach((image) => {
      image.draggable = false;
    });

    track.addEventListener('dragstart', (event) => event.preventDefault());

    const getStep = () => {
      const firstCard = track.querySelector('.cws-destination-card');
      const gap = Number.parseFloat(window.getComputedStyle(track).columnGap) || 0;
      return firstCard ? firstCard.getBoundingClientRect().width + gap : track.clientWidth;
    };

    const scrollByCard = (direction) => {
      track.scrollBy({
        left: direction * getStep(),
        behavior: prefersReducedMotion.matches ? 'auto' : 'smooth',
      });
    };

    track.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        scrollByCard(-1);
      }

      if (event.key === 'ArrowRight') {
        event.preventDefault();
        scrollByCard(1);
      }
    });

    let dragging = false;
    let moved = false;
    let startX = 0;
    let startScrollLeft = 0;

    track.addEventListener('pointerdown', (event) => {
      if (!['mouse', 'pen'].includes(event.pointerType) || event.button !== 0) {
        return;
      }

      event.preventDefault();
      dragging = true;
      moved = false;
      startX = event.clientX;
      startScrollLeft = track.scrollLeft;
      track.classList.add('is-dragging');
      track.setPointerCapture(event.pointerId);
    });

    track.addEventListener('pointermove', (event) => {
      if (!dragging) {
        return;
      }

      const distance = event.clientX - startX;
      moved = moved || Math.abs(distance) > 5;
      track.scrollLeft = startScrollLeft - distance;
    });

    const finishDragging = (event) => {
      if (!dragging) {
        return;
      }

      dragging = false;
      track.classList.remove('is-dragging');

      if (track.hasPointerCapture(event.pointerId)) {
        track.releasePointerCapture(event.pointerId);
      }
    };

    track.addEventListener('pointerup', finishDragging);
    track.addEventListener('pointercancel', finishDragging);

    track.addEventListener(
      'click',
      (event) => {
        if (moved || event.target.closest('[data-cws-placeholder="true"]')) {
          event.preventDefault();
        }

        moved = false;
      },
      true
    );
  });
})();
