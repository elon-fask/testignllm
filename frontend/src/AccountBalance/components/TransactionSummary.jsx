import React from 'react';
import { formatMoney } from 'accounting';

const TransactionSummary = ({ tSummary }) => {
  return (
    <table className="table table-condensed table-striped">
      <tbody>
        <tr>
          <td colSpan="2">Total Charged</td>
          <td>{formatMoney(tSummary.totalCharges)}</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colSpan="2">Total Adjustments</td>
          <td>{formatMoney(tSummary.totalAdjustments)}</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colSpan="2">Total Discounts</td>
          <td>{formatMoney(tSummary.totalDiscounts)}</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colSpan="2">Total Refunded</td>
          <td>{formatMoney(tSummary.totalRefunds)}</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colSpan="2">Total Payments</td>
          <td>{formatMoney(tSummary.amountPaid)}</td>
          <td>&nbsp;</td>
        </tr>
      </tbody>
    </table>
  );
};

export default TransactionSummary;
