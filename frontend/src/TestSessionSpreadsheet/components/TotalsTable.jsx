import React from 'react';
import { formatMoney } from 'accounting';

const TotalsTable = props => (
  <div style={{ display: 'flex', justifyContent: 'center' }}>
    <table className="spreadsheet">
      <thead>
        <tr className="tableHeader--upper">
          <td colSpan={7}>NCCCO Fees</td>
        </tr>
        <tr>
          <td>Practical Charges</td>
          <td>Testing</td>
          <td>Late Fee</td>
          <td>Incomplete Fee</td>
          <td>Walk-in Fee</td>
          <td>Other Fee</td>
          <td>Practice Time Charges</td>
          <td>Customer Charges</td>
          <td>Paid</td>
          <td>Amount Due</td>
        </tr>
      </thead>
      <tbody>
        <tr style={{ backgroundColor: 'rgb(216, 227, 188)' }}>
          <td>{formatMoney(props.totalPracticalCharges)}</td>
          <td>{formatMoney(props.totalWrittenNcccoFees)}</td>
          <td>{formatMoney(props.totalLateFee)}</td>
          <td>{formatMoney(props.totalIncompleteFee)}</td>
          <td>{formatMoney(props.totalWalkInFee)}</td>
          <td>{formatMoney(props.totalOtherFee)}</td>
          <td>{formatMoney(props.totalPracticeTimeCharges)}</td>
          <td>{formatMoney(props.totalCustomerCharges)}</td>
          <td>{formatMoney(props.totalPaid)}</td>
          <td>{formatMoney(props.totalDue)}</td>
        </tr>
      </tbody>
    </table>
  </div>
);

export default TotalsTable;
