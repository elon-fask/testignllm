import React from 'react';
import { render } from 'react-dom';
import Main from './components/Main';

render(
  <Main transactions={transactions} companies={companies} testSites={testSites} />,
  document.getElementById('react-entry')
);
