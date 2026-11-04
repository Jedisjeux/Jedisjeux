// Front-end entrypoint, the order of the stylesheets matters: Bootstrap 4, the base styles, the rules of the former
// template, the helpers, then the editorial design
import 'bootstrap/dist/css/bootstrap.min.css';
import './css/animations.css';
import './styles/components.css';
import './styles/base.css';

// Rules of the former template (2016 "The Project" theme, compiled from its Sass) still used by the pages, split by topic.
// They are meant to be folded into editorial.css, or dropped, little by little. Keep this order: the cascade relies on it.
import './styles/legacy/layout.css';
import './styles/legacy/header.css';
import './styles/legacy/buttons.css';
import './styles/legacy/navs.css';
import './styles/legacy/pagination.css';
import './styles/legacy/breadcrumb.css';
import './styles/legacy/forms.css';
import './styles/legacy/blocks.css';
import './styles/legacy/social-links.css';
import './styles/legacy/scroll-to-top.css';

import './styles/helpers.css';
import './styles/editorial.css';

import './js/bootstrap-components.js';
import './js/layout.js';
import './js/gallery.js';
import './js/lightbox.js';
import './js/rating.js';
import './js/notifications.js';
import './js/product-list.js';
import './js/page-views.js';
import './js/filters.js';
import './js/form-collection.js';
import './js/entities.js';
