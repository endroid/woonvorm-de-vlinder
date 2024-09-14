import * as Turbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/+esm';
import * as Stimulus from 'https://cdn.jsdelivr.net/npm/@hotwired/stimulus@3.2.2/+esm';

// Controller that displays the date
class DateController extends Stimulus.Controller {
  connect() {
    this.element.textContent = new Date().toLocaleDateString('nl-NL', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
  }
}

// start application and register controller
const application = Stimulus.Application.start();
application.register('date', DateController);
