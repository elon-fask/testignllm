import _sortBy from 'lodash/sortBy';
import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import {
  closeDialog,
  blurCell,
  setVisibleColumns,
  updateCandidateChecklist,
  bulkUpdateCandidateChecklist,
  createTransaction,
  updateTransactionRemarks,
  deleteTransaction,
  approvePendingTransaction,
  setNcccoTestFeesCredit,
  updatePracticalTestSchedule,
  resetCandidateGrades
} from '../../actionCreators';
import { dialogTypes } from '../../reducers/ui';
import { prepareMainTableData, getPracticalOnlyCandidateState } from '../../lib/helpers';
import ErrorDialog from './ErrorDialog';
import ConfirmDialog from './ConfirmDialog';
import ApplicationTypeDialog from './ApplicationTypeDialog';
import CustomApplicationFormDialog from './CustomApplicationFormDialog';
import PaymentDialog from './PaymentDialog';
import GradesDialog from './GradesDialog';
import BatchGradeDialog from './BatchGradeDialog';
import CandidateChecklistDialog from './CandidateChecklistDialog';
import ColumnOptionsDialog from './ColumnOptionsDialog';
import TestFeesCreditDialog from './TestFeesCreditDialog';
import PracticalTestScheduleDialog from './PracticalTestScheduleDialog';
import CompanyPaymentDialog from './CompanyPaymentDialog';
import PayeeTypeDialog from './PayeeTypeDialog';

const Dialog = props => {
  const commonProps = {
    isOpen: props.isOpen,
    closeDialog: props.closeDialog,
    data: props.data
  };

  switch (props.type) {
    case dialogTypes.ERROR: {
      return <ErrorDialog {...commonProps} />;
    }
    case dialogTypes.CONFIRM: {
      return <ConfirmDialog {...commonProps} />;
    }
    case dialogTypes.APPLICATION_TYPE: {
      return (
        <ApplicationTypeDialog
          candidateName={props.candidates[props.data.candidateID].name}
          applicationTypes={props.applicationTypes}
          applicationTypeIDs={props.applicationTypeIDs}
          blurCell={props.blurCell}
          {...commonProps}
        />
      );
    }
    case dialogTypes.CUSTOM_APPLICATION_FORM: {
      return (
        <CustomApplicationFormDialog
          applicationTypes={props.applicationTypes}
          blurCell={props.blurCell}
          {...commonProps}
        />
      );
    }
    case dialogTypes.PAYMENT: {
      const candidate = props.candidates[props.data.candidateID];
      const applicationType = props.applicationTypes[candidate.applicationTypeID];

      return (
        <PaymentDialog
          candidate={candidate}
          applicationType={applicationType}
          blurCell={props.blurCell}
          createTransaction={props.createTransaction}
          updateTransactionRemarks={props.updateTransactionRemarks}
          deleteTransaction={props.deleteTransaction}
          approvePendingTransaction={props.approvePendingTransaction}
          {...commonProps}
        />
      );
    }
    case dialogTypes.GRADES: {
      return (
        <GradesDialog
          candidate={props.candidates[props.data.candidateID]}
          applicationType={props.applicationTypes[props.candidates[props.data.candidateID].applicationTypeID]}
          blurCell={props.blurCell}
          resetCandidateGrades={props.resetCandidateGrades}
          {...commonProps}
        />
      );
    }
    case dialogTypes.CANDIDATE_CHECKLIST: {
      return (
        <CandidateChecklistDialog
          candidate={props.candidates[props.data.candidateID]}
          blurCell={props.blurCell}
          updateCandidateChecklist={props.updateCandidateChecklist}
          bulkUpdateCandidateChecklist={props.bulkUpdateCandidateChecklist}
          {...commonProps}
        />
      );
    }
    case dialogTypes.COLUMN_OPTIONS: {
      return (
        <ColumnOptionsDialog
          {...commonProps}
          viewOptions={props.viewOptions}
          visibleColumns={props.visibleColumns}
          setVisibleColumns={props.setVisibleColumns}
        />
      );
    }
    case dialogTypes.TEST_FEES_CREDIT: {
      return <TestFeesCreditDialog {...commonProps} setNcccoTestFeesCredit={props.setNcccoTestFeesCredit} />;
    }
    case dialogTypes.PRACTICAL_TEST_SCHEDULE: {
      const [candidateIds, candidates] = getPracticalOnlyCandidateState(props.state);

      const sortedCandidateIds = _sortBy(candidateIds, candidateId => {
        const candidate = candidates[candidateId];
        return candidate.name;
      });

      const candidateOptions = sortedCandidateIds.map(candidateId => {
        const { name, practiceTimeCredits } = candidates[candidateId];
        return {
          key: candidateId,
          value: candidateId,
          text: name,
          practiceTimeCredits
        };
      });

      return (
        <PracticalTestScheduleDialog
          {...commonProps}
          testSession={props.testSession}
          candidateOptions={candidateOptions}
          updatePracticalTestSchedule={props.updatePracticalTestSchedule}
        />
      );
    }
    case dialogTypes.BATCH_GRADE: {
      const preparedState = prepareMainTableData(props.state);
      const { candidates, candidateIDs } = preparedState;

      return <BatchGradeDialog {...commonProps} candidates={candidates} candidateIDs={candidateIDs} />;
    }
    case dialogTypes.COMPANY_PAYMENT: {
      const preparedState = prepareMainTableData(props.state);

      const { candidates, candidateIDs } = preparedState;
      const filteredCandidateIDs = candidateIDs.filter(id => {
        const candidate = candidates[id];
        return candidate.paymentStatus !== 'Paid in Full';
      });

      return (
        <CompanyPaymentDialog
          {...commonProps}
          candidates={candidates}
          candidateIDs={filteredCandidateIDs}
          companies={props.companies}
        />
      );
    }
    case dialogTypes.PAYEE_TYPE: {
      return (
        <PayeeTypeDialog
          candidate={props.candidates[props.data.candidateID]}
          blurCell={props.blurCell}
          {...commonProps}
        />
      );
    }
    default: {
      return <ErrorDialog {...commonProps} />;
    }
  }
};

const mapStateToProps = state => ({
  state,
  applicationTypes: state.applicationTypes,
  applicationTypeIDs: state.applicationTypeIDs,
  candidates: state.candidates,
  companies: state.companies,
  testSession: state.testSession,
  ...state.ui.dialog,
  visibleColumns: state.ui.columns,
  viewOptions: state.ui.options
});

const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      blurCell,
      setVisibleColumns,
      updateCandidateChecklist,
      bulkUpdateCandidateChecklist,
      closeDialog,
      createTransaction,
      updateTransactionRemarks,
      deleteTransaction,
      approvePendingTransaction,
      setNcccoTestFeesCredit,
      updatePracticalTestSchedule,
      resetCandidateGrades
    },
    dispatch
  );

export default connect(mapStateToProps, mapDispatchToProps)(Dialog);
