import React from 'react';
import { render } from 'react-dom';
import Main from './components/Main';

/* eslint-disable no-undef */
const props = {
  candidate,
  cPhotoBaseUrl: CANDIDATE_PHOTO_BASE_URL,
  tPhotoBaseUrl: TEST_SESSION_PHOTO_BASE_URL
};
/* eslint-enable no-undef */

render(<Main {...props} />, document.getElementById('react-entry'));
