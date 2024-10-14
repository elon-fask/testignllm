import React, { Fragment } from 'react';
import { connect } from 'react-redux';
import { viewTypes, viewOptions } from '../reducers/ui';
import { prepareMainTableData, splitCandidateState, preparePracticalTestScheduleTableData } from '../lib/helpers';
import TestSessionTitle from './TestSessionTitle';
import ExportMenu from './ExportMenu';
import TestSessionInfoSection from './TestSessionInfoSection';
import FixedTableHead from './Table/FixedTableHead';
import FixedTableBody from './Table/FixedTableBody';
import MainTableHead from './Table/MainTableHead';
import MainTableBody from './Table/MainTableBody';
import TotalsTable from './TotalsTable';
import SummaryTable from './SummaryTable';

const MainTable = props => {
  const { rowHeight } = viewOptions[props.view];

  const nameOnlyTableProps = {
    ...props,
    view: 'CUSTOM',
    visibleColumns: ['name', 'company']
  };

  return (
    <div style={{ display: 'flex', justifyContent: 'center', width: '100%' }} className="page-break">
      <div style={{ marginBottom: '30px' }}>
        <table className="spreadsheet is-fixed" style={{ tableLayout: 'fixed' }}>
          <FixedTableHead view="CUSTOM" visibleColumns={['name', 'company']} title={props.title} />
          <FixedTableBody {...nameOnlyTableProps} originalView={props.view} rowHeight={rowHeight} />
        </table>
      </div>
      <div className="container--spreadsheet">
        <table className="spreadsheet" style={{ tableLayout: 'fixed' }}>
          <MainTableHead view={props.view} visibleColumns={props.visibleColumns} title={props.title} />
          <MainTableBody {...props} rowHeight={rowHeight} />
        </table>
      </div>
    </div>
  );
};

const getTableProps = (props, key) => {
  const {
    candidateIDs,
    candidates,
    applicationTypes,
    numCoreExam,
    numCranesWrittenSW,
    numCranesWrittenFX,
    numCranesPracticalSW,
    numCranesPracticalFX,
    totalPracticalCharges,
    totalPracticalRetestFee,
    totalWrittenNcccoFees,
    totalLateFee,
    totalIncompleteFee,
    totalWalkInFee,
    totalOtherFee,
    lessThanFee,
    totalPracticeTimeCharges,
    totalCustomerCharges,
    totalPaid,
    totalDue
  } = key ? props[key] : props;

  return {
    candidateIDs,
    candidates,
    applicationTypes,
    numCoreExam,
    numCranesWrittenSW,
    numCranesWrittenFX,
    numCranesPracticalSW,
    numCranesPracticalFX,
    totalPracticalCharges,
    totalPracticalRetestFee,
    totalWrittenNcccoFees,
    totalLateFee,
    totalIncompleteFee,
    totalWalkInFee,
    totalOtherFee,
    lessThanFee,
    totalPracticeTimeCharges,
    totalCustomerCharges,
    totalPaid,
    totalDue
  };
};

const TableContainer = props => {
  const {
    candidateIDs,
    totalWrittenNcccoFees,
    totalPracticalCharges,
    numCranesPracticalSW,
    numCranesPracticalFX,
    totalNcccoPracticalFees,
    totalNcccoWrittenOtherFees,
    totalNcccoWrittenFees
  } = props;

  const regularTableProps = getTableProps(props, 'regularCandidateTableProps');
  const practicalOnlyTableProps = getTableProps(props, 'practicalOnlyCandidateTableProps');

  const summaryTableProps = {
    candidateIDs,
    totalPracticalCharges,
    totalWrittenNcccoFees,
    numCranesPracticalSW,
    numCranesPracticalFX,
    totalNcccoPracticalFees,
    totalNcccoWrittenOtherFees,
    lessThanFee: regularTableProps.lessThanFee,
    ncccoTestFeesCredit: props.ncccoTestFeesCredit,
    totalNcccoWrittenFees
  };

  const totalsTableProps = getTableProps(props);
  const hasTestSession = Object.keys(props.testSession).length > 0;

  let mainArea = null;

  if (props.viewOptions.combineStudents) {
    if (props.view === viewTypes.PRACTICAL_TEST_SCHEDULE) {
      const newProps = preparePracticalTestScheduleTableData(props);

      mainArea = <MainTable {...newProps} table="regular" title="All Students" />;
    } else {
      mainArea = <MainTable {...props} table="regular" title="All Students" />;
    }
  } else {
    mainArea = [
      <MainTable
        key={0}
        {...regularTableProps}
        view={props.view}
        viewOptions={props.viewOptions}
        visibleColumns={props.visibleColumns}
        table="regular"
        title="Regular Students"
      />,
      <MainTable
        key={1}
        {...practicalOnlyTableProps}
        view={props.view}
        viewOptions={props.viewOptions}
        visibleColumns={props.visibleColumns}
        table="practical-only"
        title="Practical-Only Students"
      />
    ];
  }

  return (
    <div>
      {props.viewOptions.showTestSessionTitle && <TestSessionTitle title={props.testSession.name} />}
      {hasTestSession &&
        !props.printerFriendly && <ExportMenu testSessionId={props.testSession.id} view={props.view} />}
      {props.viewOptions.showTestSessionInfo && <TestSessionInfoSection testSession={props.testSession} />}
      <Fragment>
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>{mainArea}</div>
        {props.viewOptions.showTotalsTable && <TotalsTable {...totalsTableProps} />}
        {hasTestSession && props.viewOptions.showSummaryTable && <SummaryTable {...summaryTableProps} />}
      </Fragment>
    </div>
  );
};

const mapStateToProps = state => {
  const [
    regularCandidateIDs,
    regularCandidates,
    practicalOnlyCandidateIDs,
    practicalOnlyCandidates
  ] = splitCandidateState(state);

  return {
    ...prepareMainTableData(state),
    regularCandidateTableProps: {
      ...prepareMainTableData({
        ...state,
        candidateIDs: regularCandidateIDs,
        candidates: regularCandidates
      })
    },
    practicalOnlyCandidateTableProps: {
      ...prepareMainTableData({
        ...state,
        candidateIDs: practicalOnlyCandidateIDs,
        candidates: practicalOnlyCandidates
      })
    },
    testSession: state.testSession,
    printerFriendly: state.ui.printerFriendly,
    viewOptions: state.ui.options,
    view: state.ui.view,
    visibleColumns: state.ui.columns
  };
};

export default connect(mapStateToProps)(TableContainer);
