import injectTapEventPlugin from 'react-tap-event-plugin';
import moment from 'moment';
import React from 'react';
import ReactDOM from 'react-dom';
import { createStore, compose, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import { Provider } from 'react-redux';
import BulkRegistrationReducer from './reducers';
import BulkRegistrationContainer from './components';

injectTapEventPlugin();

/* eslint-disable no-undef */
// This block gets state from the initial page load (global scope)
const defaultState = {
  applicationTypes: applicationTypes.reduce(
    (acc, applicationType) => ({
      ...acc,
      [applicationType.id]: applicationType
    }),
    {}
  ),
  applicationTypeIds: applicationTypes.map(applicationType => applicationType.id),
  promoCodes: promoCodes.reduce(
    (acc, promoCode) => ({
      ...acc,
      [promoCode.id]: promoCode
    }),
    {}
  ),
  promoCodeIds: promoCodes.map(promoCode => promoCode.id),
  testSites: testSites.reduce(
    (acc, testSite) => ({
      ...acc,
      [testSite.id]: testSite
    }),
    {}
  ),
  testSiteIds: testSites.map(testSite => testSite.id),
  testSessions: testSessions.reduce(
    (acc, testSession) => ({
      ...acc,
      [testSession.id]: {
        testSiteId: testSession.test_site_id,
        startDate: moment(testSession.start_date, 'YYYY-MM-DD hh:mm:ss').format('MMM D'),
        endDate: moment(testSession.end_date, 'YYYY-MM-DD hh:mm:ss').format('MMM D'),
        sessionNumber: testSession.session_number
      }
    }),
    {}
  ),
  testSessionIds: testSessions.map(testSession => testSession.id)
};
/* eslint-disable no-undef */

/* eslint-disable no-underscore-dangle */
const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
/* eslint-enable no-underscore-dangle */

const enhancers = composeEnhancers(applyMiddleware(thunk));

const store = createStore(BulkRegistrationReducer, defaultState, enhancers);

const App = () => (
  <Provider store={store}>
    <BulkRegistrationContainer />
  </Provider>
);

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(<App />, document.getElementById('react-entry'));
});
