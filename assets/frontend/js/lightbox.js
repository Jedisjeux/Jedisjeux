// Zoom on the images of the galleries and plays the videos, in a native dialog
const dialog = document.createElement('dialog');
dialog.className = 'lightbox';
dialog.innerHTML = `
  <button type="button" class="lightbox__close" aria-label="Fermer">×</button>
  <button type="button" class="lightbox__nav lightbox__nav--prev" aria-label="Précédent">‹</button>
  <figure class="lightbox__figure"></figure>
  <button type="button" class="lightbox__nav lightbox__nav--next" aria-label="Suivant">›</button>
`;

const figure = dialog.querySelector('.lightbox__figure');
let items = [];
let current = 0;

const render = () => {
  const { src, title, iframe } = items[current];
  figure.replaceChildren();

  if (iframe) {
    const frame = document.createElement('iframe');
    frame.src = src;
    frame.allow = 'autoplay; fullscreen; picture-in-picture';
    frame.allowFullscreen = true;
    figure.append(frame);
  } else {
    const image = document.createElement('img');
    image.src = src;
    image.alt = title || '';
    figure.append(image);
  }

  if (title) {
    const caption = document.createElement('figcaption');
    caption.textContent = title;
    figure.append(caption);
  }

  dialog.classList.toggle('lightbox--single', items.length < 2);
};

const open = (galleryItems, index = 0) => {
  items = galleryItems;
  current = index;
  render();

  if (!dialog.isConnected) {
    document.body.append(dialog);
  }

  dialog.showModal();
};

const move = (step) => {
  if (items.length > 1) {
    current = (current + step + items.length) % items.length;
    render();
  }
};

dialog.querySelector('.lightbox__close').addEventListener('click', () => dialog.close());
dialog.querySelector('.lightbox__nav--prev').addEventListener('click', () => move(-1));
dialog.querySelector('.lightbox__nav--next').addEventListener('click', () => move(1));
// Stops the videos; the close event is asynchronous, the dialog may have been opened again meanwhile
dialog.addEventListener('close', () => {
  if (!dialog.open) {
    figure.replaceChildren();
  }
});
// Closes when clicking on the backdrop
dialog.addEventListener('click', (event) => {
  if (event.target === dialog) {
    dialog.close();
  }
});
dialog.addEventListener('keydown', (event) => {
  if (event.key === 'ArrowLeft') {
    move(-1);
  } else if (event.key === 'ArrowRight') {
    move(1);
  }
});

const toItem = (link) => ({ src: link.getAttribute('href'), title: link.getAttribute('title') });

// YouTube and Vimeo pages are played through their embedded players
const toEmbedUrl = (url) => {
  const youtube = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/);
  if (youtube) {
    return `https://www.youtube.com/embed/${youtube[1]}?autoplay=1`;
  }

  const vimeo = url.match(/vimeo\.com\/(\d+)/);
  if (vimeo) {
    return `https://player.vimeo.com/video/${vimeo[1]}?autoplay=1`;
  }

  return url;
};

document.addEventListener('click', (event) => {
  const link = event.target.closest('.slick-carousel--popup-img, .popup-img, .popup-gallery-img, .popup-iframe');

  if (!link) {
    return;
  }

  // Small screens open the videos on their own page
  if (link.matches('.popup-iframe')) {
    if (window.innerWidth > 700) {
      event.preventDefault();
      open([{ src: toEmbedUrl(link.getAttribute('href')), title: link.getAttribute('title'), iframe: true }]);
    }

    return;
  }

  // Images of the same gallery, or the game cover gallery for the images of the presentation
  const container = link.matches('.popup-gallery-img')
    ? document.querySelector('.editorial-game-cover')
    : link.closest('.content-slider-with-thumbs') || document;
  const links = Array.from(container?.querySelectorAll('.slick-carousel--popup-img, .popup-img') ?? []);
  const galleryItems = links.length > 0 ? links.map(toItem) : [toItem(link)];
  const index = Math.max(0, galleryItems.findIndex((item) => item.src === link.getAttribute('href')));

  event.preventDefault();
  open(galleryItems, index);
});
