import React, { Component, Fragment } from 'react';
import moment from 'moment';
import { formatMoney } from 'accounting';
import { transactionTypes, craneTypes } from '../../common/candidateTransactions';

const transactionColor = {
  10: 'red',
  20: 'blue'
};

class TransactionHistory extends Component {
  getRemarksSection = transaction => {
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
      <div>
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
      </div>
    );
  };

  render() {
    const { props } = this;

    return (
      <table className="table table-condensed table-striped">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th />
          </tr>
        </thead>
        <tbody>
          {props.transactions.map((transaction, i) => (
            <tr key={transaction.id} style={{ color: transactionColor[transaction.paymentType] || '#333' }}>
              <th>{moment(transaction.date_created).format('MM-DD-YYYY')}</th>
              <th>
                {transaction.chargeType > 40
                  ? transactionTypes[transaction.chargeType]
                  : transactionTypes[transaction.paymentType]}
              </th>
              <th>{formatMoney(transaction.amount)}</th>
              <th style={{ display: 'flex' }}>
                {this.getRemarksSection(transaction)}
                <button
                  type="button"
                  onClick={() => {
                    props.handleClickUpdateRemark(transaction.id, transaction.remarks);
                  }}
                  data-toggle="modal"
                  data-target="#modal"
                  style={{ border: 'none', background: 'none', color: '#337ab7' }}
                >
                  <i className="fa fa-pencil" style={{ marginLeft: '10px' }} />
                </button>
              </th>
              <th>
                <button
                  type="button"
                  onClick={() => {
                    props.handleClickDeleteTransaction(transaction.id);
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
  }
}

export default TransactionHistory;
