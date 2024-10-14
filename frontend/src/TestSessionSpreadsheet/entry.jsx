import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';
import injectTapEventPlugin from 'react-tap-event-plugin';
import { applyMiddleware, createStore, compose } from 'redux';
import thunk from 'redux-thunk';
import _reduce from 'lodash/reduce';
import moment from 'moment';
import MainLayout from './components/MainLayout';
import RootReducer from './reducers';
import { uiDefaultState, viewOptions, views } from './reducers/ui';
import { getCheckedFees } from './lib/helpers';
import { parseFormSetup } from '../common/applicationForms';
import { parseGrades } from '../common/grades';
import { parseOptions } from '../common/ui';

injectTapEventPlugin();

/* eslint-disable no-undef */

const applicationTypesPrepared = applicationTypes.reduce((acc, applicationType) => {
  const applicationFormsMerged = applicationType.applicationForms.reduce(
    (applicationFormsAcc, applicationForm) => ({
      ...applicationFormsAcc,
      ...JSON.parse(applicationForm.form_setup)
    }),
    {}
  );

  const isRecert = !!applicationType.applicationForms.find(
    applicationForm => applicationForm.form_name === 'iai-blank-recert-with-1000-hours-application'
  );

  const formSetup = {
    coreEnabled: applicationFormsMerged.W_EXAM_CORE === 'on',
    writtenSWEnabled: applicationFormsMerged.W_EXAM_TLL === 'on',
    writtenFXEnabled: applicationFormsMerged.W_EXAM_TSS === 'on',
    practicalSWEnabled: applicationFormsMerged.P_TELESCOPIC_TLL === 'on',
    practicalFXEnabled: applicationFormsMerged.P_TELESCOPIC_TSS === 'on',
    lateFeeEnabled: applicationFormsMerged.W_FEE_LATE === 'on',
    incompleteFeeEnabled: applicationFormsMerged.W_FEE_INCOMPLETE === 'on'
  };

  const checkedFees = getCheckedFees(applicationFormsMerged, isRecert);

  return {
    ...acc,
    [applicationType.id]: {
      id: applicationType.id,
      name: applicationType.name,
      keyword: applicationType.keyword,
      description: applicationType.description,
      displayName: `${applicationType.name} (${applicationType.keyword}) $${applicationType.price} ${
        applicationType.description
      }`,
      price: applicationType.price,
      isRecert,
      formSetup,
      checkedFees
    }
  };
}, {});

const preloadedState = {
  testSession,
  applicationTypeIDs: applicationTypes.map(({ id }) => id),
  applicationTypes: applicationTypesPrepared,
  candidateIDs: candidates.map(({ id }) => id),
  candidates: candidates.reduce((acc, candidate) => {
    const parsedCustomFormSetup = candidate.customFormSetup === '[]' ? {} : JSON.parse(candidate.customFormSetup);
    const customFormSetupMerged = _reduce(
      parsedCustomFormSetup,
      (mergedFormAcc, value) => ({
        ...mergedFormAcc,
        ...value
      }),
      {}
    );
    const customFormSetup = parseFormSetup(customFormSetupMerged);

    const { isRecert } = applicationTypesPrepared[candidate.applicationTypeId];
    const customCheckedFees = getCheckedFees(customFormSetupMerged, isRecert);

    return {
      ...acc,
      [candidate.id]: {
        id: candidate.id,
        idHash: candidate.idHash,
        name: candidate.name,
        company: candidate.company,
        cellNumber: candidate.cellNumber || candidate.phoneNumber,
        applicationTypeID: candidate.applicationTypeId,
        isPurchaseOrder: candidate.isPurchaseOrder,
        signedWFormReceived: candidate.signedWFormReceived
          ? moment(candidate.signedWFormReceived, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
          : '--',
        signedPFormReceived: candidate.signedPFormReceived
          ? moment(candidate.signedPFormReceived, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
          : '--',
        confirmationEmailLastSent: candidate.confirmationEmailLastSent
          ? moment(candidate.confirmationEmailLastSent, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
          : '--',
        appFormSentToNccco: candidate.appFormSentToNccco
          ? moment(candidate.appFormSentToNccco, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
          : '--',
        writtenNcccoFeeOverride: candidate.writtenNcccoFeeOverride
          ? parseFloat(candidate.writtenNcccoFeeOverride)
          : undefined,
        practicalNcccoFeeOverride: candidate.practicalNcccoFeeOverride
          ? parseFloat(candidate.practicalNcccoFeeOverride)
          : undefined,
        customFormSetup,
        customCheckedFees,
        transactions: candidate.transactions,
        pendingTransactions: candidate.pendingTransactions,
        isCompanySponsored: candidate.isCompanySponsored,
        invoiceNumber: candidate.invoiceNumber ? candidate.invoiceNumber : '',
        purchaseOrderNumber: candidate.purchaseOrderNumber ? candidate.purchaseOrderNumber : '',
        collectPaymentOverride: !!candidate.collectPaymentOverride,
        grades: parseGrades(candidate.grades),
        previousGrades: candidate.previousGrades,
        instructorNotes: candidate.instructorNotes,
        practiceTimeCredits: parseFloat(candidate.practiceTimeCredits) || null
      }
    };
  }, {}),
  companies,
  ui: {
    ...uiDefaultState,
    printerFriendly,
    view,
    columns: columns || views[view],
    options: options ? parseOptions(options) : viewOptions[view]
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
    <MainLayout />
  </Provider>
);

render(<App />, document.getElementById('react-entry'));
