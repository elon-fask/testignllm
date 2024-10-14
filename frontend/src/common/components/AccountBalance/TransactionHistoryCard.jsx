import React, { Component, Fragment } from 'react';
import { withFormik, Field } from 'formik';
import TextField from '../formik/TextField';
import Dialog from 'material-ui/Dialog';
import { Card, CardHeader, CardText } from 'material-ui/Card';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton/FlatButton';
import TransactionTotalsTable from './TransactionTotalsTable';
import TransactionHistoryTable from './TransactionHistoryTable';

const defaultState = {
  isDialogOpen: false,
  dialogTransactionId: null,
  dialogType: null
};

const ConfirmDeleteDialog = props => (
  <Dialog
    title="Confirm Delete"
    actions={[
      <FlatButton primary label="Cancel" onClick={props.handleCloseDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton primary label="Confirm" onClick={props.handleClickConfirmDelete} />
    ]}
    modal
    open={props.open}
  >
    Are you would like to delete transaction?
  </Dialog>
);

const UpdateRemarksDialogBase = props => (
  <Dialog
    title="Update Remarks"
    actions={[
      <FlatButton primary label="Cancel" onClick={props.handleCloseDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton primary label="Update" onClick={props.handleSubmit} />
    ]}
    modal
    open={props.open}
  >
    <form onSubmit={props.handleSubmit}>
      <Field name="remarks" label="Remarks" component={TextField} multiLine rows={3} style={{ width: '400px' }} />
    </form>
  </Dialog>
);

const UpdateRemarksDialog = withFormik({
  handleSubmit: (values, { props }) => {
    props.handleUpdateRemarks(values.remarks || null);
  },
  mapPropsToValues: props => {
    const transaction = props.transactions.find(tx => tx.id === parseInt(props.dialogTransactionId, 10));

    return {
      remarks: transaction.remarks || ''
    };
  }
})(UpdateRemarksDialogBase);

const TransactionDialog = props => {
  if (props.type === 'DELETE') {
    return <ConfirmDeleteDialog {...props} />;
  }

  if (props.type === 'UPDATE_REMARKS') {
    return <UpdateRemarksDialog {...props} />;
  }

  return null;
};

class TransactionHistoryCard extends Component {
  state = defaultState;

  handleClickUpdateRemarks = id => {
    this.setState({ isDialogOpen: true, dialogTransactionId: id, dialogType: 'UPDATE_REMARKS' });
  };

  handleUpdateRemarks = remarks => {
    this.props
      .updateTransactionRemarks(this.state.dialogTransactionId, remarks)
      .then(() => {
        this.setState(defaultState);
      })
      .catch(err => {
        console.error(err);
      });
  };

  handleClickDelete = id => {
    this.setState({ isDialogOpen: true, dialogTransactionId: id, dialogType: 'DELETE' });
  };

  handleClickConfirmDelete = () => {
    this.props
      .deleteTransaction(this.state.dialogTransactionId)
      .then(() => {
        this.setState(defaultState);
      })
      .catch(err => {
        console.error(err);
      });
  };

  handleCloseDialog = () => {
    this.setState(defaultState);
  };

  render() {
    return (
      <Fragment>
        <Card style={{ marginBottom: '20px' }}>
          <CardHeader
            title="Transaction History"
            style={{ backgroundColor: 'rgb(232, 232, 232)' }}
            titleStyle={{ fontSize: '18px' }}
          />
          <CardText>
            <TransactionTotalsTable {...this.props.transactionSummary} />
            <TransactionHistoryTable
              transactions={this.props.transactions}
              handleClickDelete={this.handleClickDelete}
              handleClickUpdateRemarks={this.handleClickUpdateRemarks}
            />
          </CardText>
        </Card>
        <TransactionDialog
          open={this.state.isDialogOpen}
          type={this.state.dialogType}
          dialogTransactionId={this.state.dialogTransactionId}
          transactions={this.props.transactions}
          handleCloseDialog={this.handleCloseDialog}
          handleClickConfirmDelete={this.handleClickConfirmDelete}
          handleUpdateRemarks={this.handleUpdateRemarks}
        />
      </Fragment>
    );
  }
}

export default TransactionHistoryCard;
