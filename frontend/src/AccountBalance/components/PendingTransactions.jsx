import React from 'react';
import moment from 'moment';
import { formatMoney } from 'accounting';
import { transactionTypes } from '../../common/candidateTransactions';

const transactionColor = {
  10: 'red',
  20: 'blue'
};

const PendingTransactions = props => (
  <table className="table table-condensed table-striped">
    <thead>
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Amount</th>
        <th>Remarks</th>
        <th>Posted By</th>
        <th />
      </tr>
    </thead>
    <tbody>
      {props.transactions.map((transaction, i) => (
        <tr key={transaction.id} style={{ color: transactionColor[transaction.paymentType] || '#333' }}>
          <th>{moment(transaction.date_created).format('MM-DD-YYYY')}</th>
          <th>{transactionTypes[transaction.type]}</th>
          <th>{formatMoney(transaction.amount)}</th>
          <th>
            <span>
              {transaction.paymentType === 2 && transaction.check_number
                ? `Check Number: ${transaction.check_number}`
                : transaction.remarks}
            </span>
          </th>
          <th>{transaction.postedBy}</th>
          <th>
            <button
              type="button"
              onClick={() => {
                props.handleClickApprovePendingTransaction(transaction.id);
              }}
              data-toggle="modal"
              data-target="#modal"
              style={{ border: 'none', background: 'none' }}
            >
              <i className="fa fa-check" aria-hidden="true" style={{ color: 'green' }} />
            </button>
            <button
              type="button"
              onClick={() => {
                props.handleClickDeleteTransaction(transaction.id, true);
              }}
              data-toggle="modal"
              data-target="#modal"
              style={{ border: 'none', background: 'none' }}
            >
              <i className="fa fa-trash" aria-hidden="true" style={{ color: 'red' }} />
            </button>
          </th>
        </tr>
      ))}
    </tbody>
  </table>
);

export default PendingTransactions;
