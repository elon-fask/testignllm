import React from 'react';
import { formatMoney } from 'accounting';
import { Table, TableBody, TableRow, TableRowColumn } from 'material-ui/Table';

const TransactionTotalsTable = props => (
  <Table selectable={false} style={{ marginBottom: '20px' }}>
    <TableBody displayRowCheckbox={false} stripedRows>
      <TableRow>
        <TableRowColumn>Total Charged</TableRowColumn>
        <TableRowColumn>{formatMoney(props.totalCharges)}</TableRowColumn>
      </TableRow>
      <TableRow>
        <TableRowColumn>Total Adjustments</TableRowColumn>
        <TableRowColumn>{formatMoney(props.totalAdjustments)}</TableRowColumn>
      </TableRow>
      <TableRow>
        <TableRowColumn>Total Discounts</TableRowColumn>
        <TableRowColumn>{formatMoney(props.totalDiscounts)}</TableRowColumn>
      </TableRow>
      <TableRow>
        <TableRowColumn>Total Refunded</TableRowColumn>
        <TableRowColumn>{formatMoney(props.totalRefunds)}</TableRowColumn>
      </TableRow>
      <TableRow>
        <TableRowColumn>Total Payments</TableRowColumn>
        <TableRowColumn>{formatMoney(props.amountPaid)}</TableRowColumn>
      </TableRow>
    </TableBody>
  </Table>
);

export default TransactionTotalsTable;
