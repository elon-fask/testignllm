import React from 'react';
import { render } from 'react-dom';
import { convertCustomFormSetupToArray, parseApplicationForms } from '../common/applicationForms';
import { parseGrades } from '../common/grades';
import Spinner from '../common/components/Spinner';

const { Suspense, lazy } = React;

const Main = lazy(() => import('./components/Main'));
const NcccoFeesSection = lazy(() => import('./components/NcccoFeesSection'));
const CandidateManagementPanel = lazy(() => import('./CandidateManagement'));

const { transactions } = reactCandidate;
const grades = parseGrades(reactCandidate.grades);

const candidateFormFields = convertCustomFormSetupToArray(reactCandidate.custom_form_setup);

const mergedFormSetup = parseApplicationForms([
  ...reactCandidate.applicationType.applicationFormSetups,
  ...candidateFormFields
]);

const hasApplicationCharge = transactions.reduce((transactionsAcc, transaction) => {
  if (transaction.paymentType === 10 && !transaction.chargeType) {
    return true;
  }
  return transactionsAcc || false;
}, false);

let practicalCharges = 0;

if (mergedFormSetup.formSetup.practicalSWEnabled || mergedFormSetup.formSetup.practicalFXEnabled) {
  practicalCharges = grades.P_TELESCOPIC_TLL === 'Did Not Test' || grades.P_TELESCOPIC_TSS === 'Did Not Test' ? 0 : 60;
}

if (mergedFormSetup.formSetup.practicalSWEnabled && mergedFormSetup.formSetup.practicalFXEnabled) {
  practicalCharges = 70;
  if (grades.P_TELESCOPIC_TLL === 'Did Not Test' || grades.P_TELESCOPIC_TSS === 'Did Not Test') {
    practicalCharges = 60;
  }
  if (grades.P_TELESCOPIC_TLL === 'Did Not Test' && grades.P_TELESCOPIC_TSS === 'Did Not Test') {
    practicalCharges = 0;
  }
}

if (!hasApplicationCharge && reactCandidate.applicationType.price > 0) {
  practicalCharges = 0;
}

const candidate = {
  ...reactCandidate,
  practiceTimeCredits: parseFloat(reactCandidate.practice_time_credits) || 0
};

/* eslint-disable no-undef */
render(
  <div>
    <Suspense fallback={<Spinner />} maxDuration={2000}>
      <Main candidate={candidate} practicalTestSessionId={reactPracticalTestSessionId} />
    </Suspense>
  </div>,
  document.getElementById('react-entry')
);

render(
  <div>
    <Suspense fallback={<Spinner />} maxDuration={2000}>
      <NcccoFeesSection
        writtenNcccoFees={mergedFormSetup.totalFees}
        practicalNcccoFees={practicalCharges}
        writtenNcccoFeeOverride={reactCandidate.written_nccco_fee_override}
        practicalNcccoFeeOverride={reactCandidate.practical_nccco_fee_override}
      />
    </Suspense>
  </div>,
  document.getElementById('react-entry-nccco-fees')
);

render(
  <div>
    <Suspense fallback={<Spinner />} maxDuration={2000}>
      <CandidateManagementPanel candidate={candidate} modalId="modal-candidate-mgmt" />
    </Suspense>
  </div>,
  document.getElementById('react-entry-cert')
);
/* eslint-enable no-undef */
