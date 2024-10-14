import injectTapEventPlugin from 'react-tap-event-plugin';
import React from 'react';
import ReactDOM from 'react-dom';
import CalendarComponent from './components';

injectTapEventPlugin();

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(<CalendarComponent />, document.getElementById('react-entry'));
});
