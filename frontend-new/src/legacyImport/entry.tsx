import * as React from 'react';
import { render } from 'react-dom';
import App from '../layout/default';
import Main from './Main';

render(
  <App>
    <Main />
  </App>,
  document.getElementById('react-entry')
);
