import React from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { formatMoney } from 'accounting';
import { openDialog } from '../actionCreators';
import { dialogTypes } from '../reducers/ui';

const SummaryTable = props => (
  <div style={{ marginTop: '20px', display: 'flex', justifyContent: 'center' }}>
    <table className="spreadsheet table--summary">
      <tbody>
        <tr>
          <td>Total Candidates:</td>
          <td>{props.candidateIDs.length}</td>
        </tr>
        <tr>
          <td>Total SW Cranes:</td>
          <td>{props.numCranesPracticalSW}</td>
        </tr>
        <tr>
          <td>Total FX Cranes:</td>
          <td>{props.numCranesPracticalFX}</td>
        </tr>
      </tbody>
    </table>
    <table className="spreadsheet table--summary">
      <tbody>
        <tr>
          <td>Total NCCCO Practical Exam and Retest Fees:</td>
          <td>{formatMoney(props.totalPracticalCharges)}</td>
        </tr>
        <tr>
          <td>50% Provided by NCCCO:</td>
          <td>({formatMoney(props.totalPracticalCharges / 2)})</td>
        </tr>
        <tr>
          <td>Total NCCCO Practical Fees:</td>
          <td>{formatMoney(props.totalNcccoPracticalFees)}</td>
        </tr>
      </tbody>
    </table>
    <table className="spreadsheet table--summary">
      <tbody>
        <tr>
          <td>Total NCCCO Written Test Fees:</td>
          <td>{formatMoney(props.totalWrittenNcccoFees)}</td>
        </tr>
        <tr>
          <td>50% Provided by NCCCO:</td>
          <td>({formatMoney(props.totalWrittenNcccoFees / 2)})</td>
        </tr>
        <tr>
          <td>Total NCCCO Written Other Fees:</td>
          <td>{formatMoney(props.totalNcccoWrittenOtherFees)}</td>
        </tr>
        {props.lessThanFee > 0 && (
          <tr>
            <td>Applicable &quot;Less Than&quot; Fee:</td>
            <td>{formatMoney(props.lessThanFee)}</td>
          </tr>
        )}
        <tr
          onClick={() => {
            props.openDialog(dialogTypes.TEST_FEES_CREDIT, { amount: props.ncccoTestFeesCredit });
          }}
        >
          <td>Total NCCCO Test Fees Credit:</td>
          <td>{`(${formatMoney(props.ncccoTestFeesCredit)})`}</td>
        </tr>
        <tr>
          <td>Total NCCCO Written Fees:</td>
          <td>{formatMoney(props.totalNcccoWrittenFees)}</td>
        </tr>
      </tbody>
    </table>
  </div>
);

const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      openDialog
    },
    dispatch
  );

export default connect(null, mapDispatchToProps)(SummaryTable);
