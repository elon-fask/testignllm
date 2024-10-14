import React, { Fragment } from 'react';
import StudentDetailsCard from './AccountBalance/StudentDetailsCard';
import TransactionHistoryCard from './AccountBalance/TransactionHistoryCard';
import PendingTransactionsCard from './AccountBalance/PendingTransactionsCard';
import { summarizeTransactions } from '../candidateTransactions';

const AccountBalance = props => {
  const transactionSummary = summarizeTransactions(props.transactions, false);

  return (
    <Fragment>
      <StudentDetailsCard
        applicationType={props.applicationType}
        idHash={props.idHash}
        cellNumber={props.cellNumber}
        purchaseOrderNumber={props.purchaseOrderNumber}
        {...transactionSummary}
        createTransaction={props.createTransaction}
      />
      <TransactionHistoryCard
        transactions={props.transactions}
        transactionSummary={transactionSummary}
        updateTransactionRemarks={props.updateTransactionRemarks}
        deleteTransaction={props.deleteTransaction}
      />
      <PendingTransactionsCard
        pendingTransactions={props.pendingTransactions}
        approvePendingTransaction={props.approvePendingTransaction}
        deletePendingTransaction={props.deletePendingTransaction}
      />
    </Fragment>
  );
};

export default AccountBalance;
