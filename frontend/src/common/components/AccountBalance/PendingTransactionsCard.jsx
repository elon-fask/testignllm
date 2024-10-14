import React, { Component, Fragment } from 'react';
import moment from 'moment';
import { formatMoney } from 'accounting';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { Card, CardHeader, CardText } from 'material-ui/Card';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from '../../components/formik/TextField';

const tableHeaderStyle = {
  fontSize: '14px',
  fontWeight: 'bold',
  color: '#000'
};

const ApprovePendingTransactionDialogBase = props => (
  <Dialog
    title="Approve Pending Transaction"
    actions={[
      <RaisedButton label="Close" onClick={props.closeDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton label="Approve" primary onClick={props.handleSubmit} />
    ]}
    modal
    open={props.open}
  >
    <form onSubmit={props.handleSubmit} style={{ display: 'flex', flexDirection: 'column' }}>
      <Field name="remarks" label="Remarks" component={TextField} multiLine rows={3} style={{ width: '400px' }} />
    </form>
  </Dialog>
);

const ApprovePendingTransactionDialog = withFormik({
  handleSubmit: (values, { props: { transactionId, approvePendingTransaction, closeDialog } }) => {
    const remarks = values.remarks || undefined;
    approvePendingTransaction(transactionId, remarks)
      .then(() => {
        closeDialog();
      })
      .catch(e => {
        console.error(e);
      });
  },
  mapPropsToValues: () => ({
    remarks: ''
  }),
  validationSchema: Yup.object().shape({
    remarks: Yup.string()
  })
})(ApprovePendingTransactionDialogBase);

const DeletePendingTransactionDialog = props => (
  <Dialog
    title="Delete Pending Transaction"
    actions={[
      <RaisedButton label="Close" onClick={props.closeDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton
        label="Delete"
        primary
        onClick={() => {
          props
            .deletePendingTransaction(props.transactionId)
            .then(() => {
              props.closeDialog();
            })
            .catch(e => {
              console.error(e);
            });
        }}
      />
    ]}
    modal
    open={props.open}
  >
    <div>Are you sure you wish to delete pending transaction?</div>
  </Dialog>
);

const ActionDialog = props => {
  if (props.type === 'APPROVE_PENDING_TRANSACTION') {
    return <ApprovePendingTransactionDialog {...props} />;
  }

  if (props.type === 'DELETE_PENDING_TRANSACTION') {
    return <DeletePendingTransactionDialog {...props} />;
  }

  return null;
};

class PendingTransactionsCard extends Component {
  state = {
    isDialogOpen: false,
    dialogType: null,
    selectedTransactionId: null
  };

  handleClickApprove = transactionId => {
    this.setState({
      isDialogOpen: true,
      dialogType: 'APPROVE_PENDING_TRANSACTION',
      selectedTransactionId: transactionId
    });
  };

  handleClickDelete = transactionId => {
    this.setState({
      isDialogOpen: true,
      dialogType: 'DELETE_PENDING_TRANSACTION',
      selectedTransactionId: transactionId
    });
  };

  closeDialog = () => {
    this.setState({ isDialogOpen: false, dialogType: null });
  };

  render() {
    const { props } = this;

    return (
      <Fragment>
        <Card>
          <CardHeader
            title="Pending Transactions"
            style={{ backgroundColor: 'rgb(232, 232, 232)' }}
            titleStyle={{ fontSize: '18px' }}
          />
          <CardText>
            <Table selectable={false} style={{ marginBottom: '20px' }}>
              <TableHeader adjustForCheckbox={false} displaySelectAll={false}>
                <TableRow>
                  <TableHeaderColumn style={tableHeaderStyle}>Date</TableHeaderColumn>
                  <TableHeaderColumn style={tableHeaderStyle}>Type</TableHeaderColumn>
                  <TableHeaderColumn style={tableHeaderStyle}>Amount</TableHeaderColumn>
                  <TableHeaderColumn style={tableHeaderStyle}>Actions</TableHeaderColumn>
                </TableRow>
              </TableHeader>
              <TableBody displayRowCheckbox={false} stripedRows>
                {props.pendingTransactions.map(pendingTransaction => (
                  <TableRow key={pendingTransaction.id}>
                    <TableRowColumn>{moment(pendingTransaction.created_at).format('MM-DD-YYYY')}</TableRowColumn>
                    <TableRowColumn>Testing</TableRowColumn>
                    <TableRowColumn>{formatMoney(pendingTransaction.amount)}</TableRowColumn>
                    <TableRowColumn>
                      <RaisedButton
                        onClick={() => {
                          this.handleClickApprove(pendingTransaction.id);
                        }}
                        label={<i className="fa fa-check" aria-hidden />}
                        backgroundColor="green"
                        labelStyle={{ color: '#fff' }}
                        style={{ minWidth: 'auto', width: 'auto', marginRight: '8px' }}
                      />
                      <RaisedButton
                        onClick={() => {
                          this.handleClickDelete(pendingTransaction.id);
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
          </CardText>
        </Card>
        <ActionDialog
          open={this.state.isDialogOpen}
          type={this.state.dialogType}
          transactionId={this.state.selectedTransactionId}
          approvePendingTransaction={props.approvePendingTransaction}
          deletePendingTransaction={props.deletePendingTransaction}
          closeDialog={this.closeDialog}
        />
      </Fragment>
    );
  }
}

export default PendingTransactionsCard;
