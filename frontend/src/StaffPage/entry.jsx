import React from 'react';
import { render } from 'react-dom';
import Main from './components/Main';

render(<Main user={user} loggedInUserId={loggedInUserId} />, document.getElementById('react-entry-staff-update'));
