import React from 'react';
import { render } from 'react-dom';
import App from './components/App';

document.addEventListener('DOMContentLoaded', () => {
  render(<App testSites={testSites} />, document.getElementById('react-entry'));
});
