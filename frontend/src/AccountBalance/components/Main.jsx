import React, { Component } from 'react';
import { formatMoney } from 'accounting';
import Dialog from './Dialog';
import TransactionSummary from './TransactionSummary';
import TransactionHistory from './TransactionHistory';
import PendingTransactions from './PendingTransactions';
import { summarizeTransactions } from '../../common/candidateTransactions';
import { apiAddTransaction, apiUpdateTransaction, apiDeleteTransaction } from '../../common/api';

class Main extends Component {
  constructor(props) {
    super(props);
    this.state = {
      transactions: props.candidate.transactions,
      pendingTransactions: props.candidate.pendingTransactions,
      dialogType: 'NONE',
      currentTransactionId: '',
      editingRemark: '',
      isPending: false
    };
  }

  addTransaction = details => {
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

    return apiAddTransaction(this.props.candidate.id, payload)
      .then(({ data }) => {
        this.setState({
          transactions: [...this.state.transactions, data]
        });
        $('#modal').modal('hide');
      })
      .catch(e => {
        console.error(e);
      });
  };

  updateRemark = (transactionId, remarks) => {
    apiUpdateTransaction(this.props.candidate.id, transactionId, { remarks })
      .then(({ data }) => {
        const payload = this.state.transactions.map(transaction => {
          if (transaction.id === data.id) {
            return data;
          }
          return transaction;
        });
        this.setState({
          transactions: payload,
          currentTransactionId: '',
          editingRemark: '',
          isPending: false
        });
        $('#modal').modal('hide');
      })
      .catch(e => {
        console.error(e);
      });
  };

  deleteTransaction = transactionId => {
    const { transactions, pendingTransactions, isPending } = this.state;

    apiDeleteTransaction(this.props.candidate.id, transactionId, isPending)
      .then(() => {
        const payload = isPending
          ? pendingTransactions.filter(({ id }) => id !== transactionId)
          : transactions.filter(({ id }) => id !== transactionId);

        const txKey = isPending ? 'pendingTransactions' : 'transactions';

        this.setState({
          [txKey]: payload,
          currentTransactionId: '',
          editingRemark: '',
          isPending: false
        });
        $('#modal').modal('hide');
      })
      .catch(e => {
        console.error(e);
      });
  };

  convertToInvoice = async () => {
    const { customerCharges, amountPaid } = summarizeTransactions(this.props.candidate.transactions, false);
    const isValid = !(customerCharges < 295) && amountPaid > 295;

    if (isValid) {
      const refundAmount = amountPaid - 295;

      const refundPayload = {
        amount: refundAmount,
        paymentType: 20,
        remarks: 'Converted to invoice'
      };

      const addedChargePayload = {
        amount: refundAmount,
        paymentType: 10,
        chargeType: 70,
        remarks: 'Converted to invoice'
      };

      try {
        const { data: refundTransaction } = await apiAddTransaction(this.props.candidate.id, refundPayload);
        const { data: addedChargeTransaction } = await apiAddTransaction(this.props.candidate.id, addedChargePayload);

        this.setState(
          {
            transactions: [...this.state.transactions, refundTransaction, addedChargeTransaction]
          },
          () => {
            $('#modal').modal('hide');
          }
        );
      } catch (e) {
        console.error(e);
      }
    } else {
      $('#modal').modal('hide');
    }
  };

  approvePendingTransaction = () => {
    const { pendingTransactions, currentTransactionId } = this.state;

    const pendingTransaction = pendingTransactions.find(({ id }) => currentTransactionId === id);
    const details = {
      ...pendingTransaction,
      remarks: `Posted on-site by ${pendingTransaction.postedBy}`
    };

    this.addTransaction(details).then(() => {
      this.deleteTransaction(currentTransactionId);
    });
  };

  handleClickUpdateRemark = (transactionId, remarks) => {
    this.setState({
      dialogType: 'UPDATE_REMARKS',
      currentTransactionId: transactionId,
      editingRemark: remarks
    });
  };

  handleClickDeleteTransaction = (transactionId, pending = false) => {
    this.setState({
      dialogType: pending ? 'CONFIRM_DELETE_PENDING' : 'CONFIRM_DELETE',
      currentTransactionId: transactionId,
      isPending: pending
    });
  };

  handleClickApprovePendingTransaction = transactionId => {
    this.setState({
      dialogType: 'CONFIRM_APPROVE_PENDING',
      currentTransactionId: transactionId,
      isPending: true
    });
  };

  handleDialogClose = () => {
    this.setState({
      dialogType: 'NONE',
      currentTransactionId: '',
      editingRemark: ''
    });
  };

  render() {
    const { candidate } = this.props;
    const tSummary = summarizeTransactions(this.state.transactions, false);

    return [
      <div key={0}>
        <div className="panel panel-default">
          <div className="panel-heading">
            <h4>Student Details</h4>
          </div>
          <div className="panel-body">
            <table className="table table-condensed table-account-student-details">
              <tbody>
                <tr style={{ fontSize: '1.5em' }}>
                  <th>Name</th>
                  <td>{candidate.fullName}</td>
                </tr>
                <tr>
                  <th>Phone</th>
                  <td>{candidate.phone}</td>
                </tr>
                <tr>
                  <th>Application Type</th>
                  <td>{candidate.applicationType}</td>
                </tr>
                <tr>
                  <th>Price</th>
                  <td>{formatMoney(tSummary.customerCharges)}</td>
                </tr>
                <tr>
                  <th>Remaining Amount</th>
                  <td>{formatMoney(tSummary.amountDue)}</td>
                </tr>
                <tr>
                  <th>PO Number</th>
                  <td>{candidate.poNumber}</td>
                </tr>
                <tr>
                  <td colSpan="2">
                    <ul className="list-unstyled list-inline" style={{ paddingLeft: '200px', marginTop: '10px' }}>
                      <li>
                        <button
                          type="button"
                          className="btn btn-primary"
                          data-toggle="modal"
                          data-target="#modal"
                          onClick={() => {
                            this.setState({ dialogType: 'ADD_CHARGE' });
                          }}
                        >
                          Add Charge
                        </button>
                        <br />
                      </li>
                      <li>
                        <button
                          type="button"
                          className="btn btn-primary"
                          data-toggle="modal"
                          data-target="#modal"
                          onClick={() => {
                            this.setState({ dialogType: 'ADD_DISCOUNT' });
                          }}
                        >
                          Add Discount
                        </button>
                        <br />
                      </li>
                      <li>
                        <button
                          type="button"
                          className="btn btn-primary"
                          data-toggle="modal"
                          data-target="#modal"
                          onClick={() => {
                            this.setState({ dialogType: 'ADD_REFUND' });
                          }}
                        >
                          Add Refund
                        </button>
                      </li>
                      <li>
                        <button
                          type="button"
                          className="btn btn-primary"
                          data-toggle="modal"
                          data-target="#modal"
                          onClick={() => {
                            this.setState({ dialogType: 'ADD_ADJUSTMENT' });
                          }}
                        >
                          Make an Adjustment
                        </button>
                      </li>
                      <li>
                        <button
                          type="button"
                          className="btn btn-primary"
                          data-toggle="modal"
                          data-target="#modal"
                          onClick={() => {
                            this.setState({ dialogType: 'RECEIVE_PAYMENT' });
                          }}
                        >
                          Receive a Payment
                        </button>
                      </li>
                      <li>
                        <button
                          type="button"
                          className="btn btn-primary"
                          data-toggle="modal"
                          data-target="#modal"
                          onClick={() => {
                            this.setState({ dialogType: 'CONVERT_TO_INVOICE' });
                          }}
                        >
                          Convert to Invoice
                        </button>
                      </li>
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div className="panel panel-default">
          <div className="panel-heading">
            <h4>Transaction History</h4>
          </div>
          <div className="panel-body" id="payment-details">
            {this.state.transactions.length > 0 ? (
              [
                <TransactionSummary key={0} tSummary={tSummary} />,
                <TransactionHistory
                  key={1}
                  transactions={this.state.transactions}
                  handleClickUpdateRemark={this.handleClickUpdateRemark}
                  handleClickDeleteTransaction={this.handleClickDeleteTransaction}
                />
              ]
            ) : (
              <div className="alert alert-danger">No Payment Transactions</div>
            )}
          </div>
        </div>
        <div className="panel panel-default">
          <div className="panel-heading">
            <h4>Pending Transactions</h4>
          </div>
          <div className="panel-body" id="payment-details">
            {this.state.pendingTransactions.length > 0 ? (
              <PendingTransactions
                transactions={this.state.pendingTransactions}
                handleClickApprovePendingTransaction={this.handleClickApprovePendingTransaction}
                handleClickDeleteTransaction={this.handleClickDeleteTransaction}
              />
            ) : (
              <div className="alert alert-danger">No Pending Transactions</div>
            )}
          </div>
        </div>
      </div>,
      <Dialog
        key={1}
        type={this.state.dialogType}
        handleDialogClose={this.handleDialogClose}
        candidate={this.props.candidate}
        addTransaction={this.addTransaction}
        updateRemark={this.updateRemark}
        deleteTransaction={this.deleteTransaction}
        convertToInvoice={this.convertToInvoice}
        approvePendingTransaction={this.approvePendingTransaction}
        maxDiscount={tSummary.amountDue}
        maxRefund={tSummary.customerCharges}
        idHash={this.props.candidate.idHash}
        currentTransactionId={this.state.currentTransactionId}
        editingRemark={this.state.editingRemark}
      />
    ];
  }
}

export default Main;
