import React from 'react';
import { render } from 'react-dom';
import App from './App';

render(<App primaryUser={primaryUser} secondaryUser={secondaryUser} />, document.getElementById('react-entry'));
