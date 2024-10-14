import React from 'react';
import { render } from 'react-dom';
import App from './components/App';

/* eslint-disable no-undef */
const travelFormProps = travelForm;
/* eslint-enable no-undef */

App.displayName = 'Entry';

render(<App initialState={travelFormProps} />, document.getElementById('react-entry'));
