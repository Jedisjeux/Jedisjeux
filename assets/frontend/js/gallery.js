// Image galleries of the games, people and game plays: one image at a time, the thumbnails select the image
const initGallery = (gallery) => {
  const slides = Array.from(gallery.children);
  const thumbs = Array.from(gallery.parentElement.querySelectorAll('.content-slider-thumbs > *'));

  const show = (index) => {
    slides.forEach((slide, position) => slide.classList.toggle('is-active', position === index));
    thumbs.forEach((thumb, position) => {
      thumb.classList.toggle('is-active', position === index);
      thumb.setAttribute('aria-current', String(position === index));
    });
  };

  thumbs.forEach((thumb, index) => {
    thumb.setAttribute('role', 'button');
    thumb.tabIndex = 0;
    thumb.addEventListener('click', () => show(index));
    thumb.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        show(index);
      }
    });
  });

  gallery.classList.add('is-ready');
  show(0);
};

document.querySelectorAll('.content-slider-with-thumbs').forEach(initGallery);
