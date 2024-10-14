import React, { Component } from 'react';
import MUIDialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import AccountBalance from '../../../common/components/AccountBalance';

class PaymentDialog extends Component {
  createTransaction = details => {
    const type = parseInt(details.type, 10);

    const payload = {
      amount: details.amount,
      paymentType: type > 40 ? 10 : type,
      chargeType: type > 40 ? type : undefined,
      remarks: details.remarks || undefined
    };

    if (type === 2 && details.checkNumber) {
      payload.check_number = details.checkNumber;
    }

    if (type === 50 && details.craneSelection) {
      payload.retest_crane_selection = details.craneSelection;
    }

    return this.props.createTransaction(this.props.candidate.id, payload);
  };

  updateTransactionRemarks = (transactionId, remarks) => {
    return this.props.updateTransactionRemarks(this.props.candidate.id, transactionId, remarks);
  };

  deleteTransaction = transactionId => {
    return this.props.deleteTransaction(this.props.candidate.id, transactionId);
  };

  approvePendingTransaction = (transactionId, remarks) => {
    return this.props.approvePendingTransaction(this.props.candidate.id, transactionId, remarks);
  };

  deletePendingTransaction = transactionId => {
    return this.props.deleteTransaction(this.props.candidate.id, transactionId, true);
  };

  updateRemark = () => {};

  render() {
    const { props } = this;

    return (
      <MUIDialog
        title={`Account Balance - ${props.candidate.name}`}
        modal
        open={props.isOpen}
        contentStyle={{ maxWidth: '100%' }}
        autoScrollBodyContent
        actions={[<RaisedButton primary label="Close" style={{ marginRight: '20px' }} onClick={props.closeDialog} />]}
      >
        <AccountBalance
          applicationType={props.applicationType.name}
          idHash={props.candidate.idHash}
          cellNumber={props.candidate.cellNumber}
          purchaseOrderNumber={props.candidate.purchaseOrderNumber}
          transactions={props.candidate.transactions}
          createTransaction={this.createTransaction}
          updateTransactionRemarks={this.updateTransactionRemarks}
          deleteTransaction={this.deleteTransaction}
          pendingTransactions={props.candidate.pendingTransactions}
          approvePendingTransaction={this.approvePendingTransaction}
          deletePendingTransaction={this.deletePendingTransaction}
        />
      </MUIDialog>
    );
  }
}

export default PaymentDialog;
