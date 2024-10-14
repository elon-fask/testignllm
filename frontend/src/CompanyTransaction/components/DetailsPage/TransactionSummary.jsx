import React, { Fragment } from 'react';
import moment from 'moment';
import { formatMoney } from 'accounting';
import { companyTxTypesStr } from '../../../common/companyTransactions';

function TransactionSummary({ transactionDetails }) {
  const { companyName, amount, type, transactionId, authCode, checkNumber, postedBy, lastUpdated } = transactionDetails;

  return (
    <div className="transaction-summary">
      <div>Company:</div>
      <div>{companyName}</div>
      <div>Amount:</div>
      <div>{formatMoney(amount)}</div>
      <div>Payment Type:</div>
      <div>{companyTxTypesStr[type]}</div>
      {transactionId && (
        <Fragment>
          <div>3rd Party Transaction ID:</div>
          <div>{transactionId}</div>
        </Fragment>
      )}
      {authCode && (
        <Fragment>
          <div>Authorization Code:</div>
          <div>{authCode}</div>
        </Fragment>
      )}
      {checkNumber && (
        <Fragment>
          <div>Check Number:</div>
          <div>{checkNumber}</div>
        </Fragment>
      )}
      <div>Posted By:</div>
      <div>{postedBy}</div>
      <div>Last Updated:</div>
      <div>{moment(lastUpdated, 'YYYY-MM-DD HH:mm:ss').format('MMMM D, YYYY')}</div>
    </div>
  );
}

export default TransactionSummary;
