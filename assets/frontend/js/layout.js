// Fixed header, scroll animations and "scroll to top" button of the layout

// Fixed header on desktop: a wrapper keeps the header height in the page once it is fixed
const header = document.querySelector('.header.fixed.fixed-desktop');

if (header && window.innerWidth > 1000) {
  const wrapper = document.createElement('div');
  wrapper.className = 'sticky-wrapper';
  header.before(wrapper);
  wrapper.append(header);
  wrapper.style.height = `${header.offsetHeight}px`;

  let stuck = false;
  const update = () => {
    const shouldStick = wrapper.getBoundingClientRect().top < -1;

    if (shouldStick !== stuck) {
      stuck = shouldStick;
      header.classList.toggle('object-visible', stuck);
      document.body.classList.toggle('fixed-header-on', stuck);
    }
  };

  window.addEventListener('scroll', update, { passive: true });
  update();
}

// Animations of the elements entering the viewport
const animated = document.querySelectorAll('[data-animation-effect]');

if (animated.length > 0) {
  const observer = new IntersectionObserver((entries) => {
    entries.filter((entry) => entry.isIntersecting).forEach(({ target }) => {
      target.classList.add('animated', 'object-visible', target.dataset.animationEffect);
      observer.unobserve(target);
    });
  }, { rootMargin: '0px 0px -10% 0px' });

  animated.forEach((element) => observer.observe(element));
}

// Scroll to top
const scrollToTop = document.querySelector('.scrollToTop');

if (scrollToTop) {
  const update = () => {
    const scrolled = window.scrollY !== 0;
    scrollToTop.classList.toggle('fadeToTop', scrolled);
    scrollToTop.classList.toggle('fadeToBottom', !scrolled);
  };

  window.addEventListener('scroll', update, { passive: true });
  scrollToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}
