import React from 'react';
import { render } from 'react-dom';
import { applyMiddleware, createStore, compose } from 'redux';
import thunk from 'redux-thunk';
import { Provider } from 'react-redux';
import injectTapEventPlugin from 'react-tap-event-plugin';
import { parseApplicationForms } from '../common/applicationForms';
import Main from './component/Main';

injectTapEventPlugin();

const RootReducer = (state = {}, action) => {
  return state;
};

/* eslint-disable no-undef */
const preloadedState = {
  candidate,
  applicationTypes: applicationTypes.map(applicationType => ({
    ...applicationType,
    ...parseApplicationForms(applicationType.applicationForms)
  })),
  currentTestSession,
  currentTestSessionCounterpart,
  incomingTestSession,
  incomingTestSessionCounterpart,
  defaults: {
    isRescheduleOnly
  },
  preset: {
    transferType,
    bothTestSessions: bothTestSessions === '1'
  }
};
/* eslint-enable no-undef */

/* eslint-disable no-underscore-dangle */
const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
/* eslint-enable no-underscore-dangle */

const enhancers = composeEnhancers(applyMiddleware(thunk));
const store = createStore(RootReducer, preloadedState, enhancers);

const App = () => (
  <Provider store={store}>
    <Main />
  </Provider>
);

render(<App />, document.getElementById('react-entry'));
