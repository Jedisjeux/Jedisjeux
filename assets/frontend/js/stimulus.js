// Stimulus application of the front-end, only the controllers it needs are registered (not the whole controllers.json
// of the admin)
import { Application } from '@hotwired/stimulus';
import LiveController from '@symfony/ux-live-component';

const application = Application.start();
application.register('live', LiveController);
