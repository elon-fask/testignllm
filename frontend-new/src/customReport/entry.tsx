import * as React from 'react';
import { render } from 'react-dom';
import App from '../layout/default';
import Main from './Main';
import { MainContextProvider } from './context';

render(
  <MainContextProvider>
    <App>
      <Main />
    </App>
  </MainContextProvider>,
  document.getElementById('react-entry')
);
