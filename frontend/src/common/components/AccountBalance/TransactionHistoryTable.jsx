import React, { Fragment } from 'react';
import moment from 'moment';
import { formatMoney } from 'accounting';
import RaisedButton from 'material-ui/RaisedButton';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import { transactionTypes, craneTypes } from '../../candidateTransactions';

const tableHeaderStyle = {
  fontSize: '14px',
  fontWeight: 'bold',
  color: '#000'
};

const getRemarksSection = transaction => {
  const details = [];
  const keys = [];

  if (transaction.retest_crane_selection) {
    details.push(`Retest Crane Selection: ${craneTypes[transaction.retest_crane_selection]}`);
  }

  if (transaction.paymentType === 2 && transaction.check_number) {
    details.push(`Check Number: ${transaction.check_number}`);
    keys.push('CHECK_NUM');
  }

  if (transaction.transactionId) {
    details.push(`Transaction ID: ${transaction.transactionId}`);
    keys.push('TX_ID');
  }

  if (transaction.auth_code) {
    details.push(`Authorization Code: ${transaction.auth_code}`);
    keys.push('AUTH_CODE');
  }

  return (
    <Fragment>
      {transaction.remarks && (
        <Fragment>
          <span>{transaction.remarks}</span>
          <br />
        </Fragment>
      )}
      {details.length > 0 && (
        <Fragment>
          <span>Additional Details:</span>
          <br />
          {details.map((detail, i) => (
            <Fragment key={keys[i]}>
              <span>{detail}</span>
              <br />
            </Fragment>
          ))}
        </Fragment>
      )}
    </Fragment>
  );
};

const TransactionHistoryTable = props => (
  <Table selectable={false} style={{ marginBottom: '20px' }}>
    <TableHeader adjustForCheckbox={false} displaySelectAll={false}>
      <TableRow>
        <TableHeaderColumn style={tableHeaderStyle}>Date</TableHeaderColumn>
        <TableHeaderColumn style={tableHeaderStyle}>Type</TableHeaderColumn>
        <TableHeaderColumn style={tableHeaderStyle}>Amount</TableHeaderColumn>
        <TableHeaderColumn style={tableHeaderStyle}>Remarks</TableHeaderColumn>
        <TableHeaderColumn style={tableHeaderStyle}>Actions</TableHeaderColumn>
      </TableRow>
    </TableHeader>
    <TableBody displayRowCheckbox={false} stripedRows>
      {props.transactions.map(transaction => (
        <TableRow key={transaction.id}>
          <TableRowColumn>{moment(transaction.date_created).format('MM-DD-YYYY')}</TableRowColumn>
          <TableRowColumn>
            {transaction.chargeType > 40
              ? transactionTypes[transaction.chargeType]
              : transactionTypes[transaction.paymentType]}
          </TableRowColumn>
          <TableRowColumn>{formatMoney(transaction.amount)}</TableRowColumn>
          <TableRowColumn>{getRemarksSection(transaction)}</TableRowColumn>
          <TableRowColumn>
            <RaisedButton
              onClick={() => {
                props.handleClickUpdateRemarks(transaction.id);
              }}
              label={<i className="fa fa-pencil" aria-hidden />}
              primary
              style={{ minWidth: 'auto', width: 'auto', marginRight: '8px' }}
            />
            <RaisedButton
              onClick={() => {
                props.handleClickDelete(transaction.id);
              }}
              label={<i className="fa fa-trash" aria-hidden />}
              backgroundColor="#d9534f"
              labelStyle={{ color: '#fff' }}
              style={{ minWidth: 'auto', width: 'auto' }}
            />
          </TableRowColumn>
        </TableRow>
      ))}
    </TableBody>
  </Table>
);

export default TransactionHistoryTable;
