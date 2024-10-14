import React, { Component, Fragment } from 'react';
import RaisedButton from 'material-ui/RaisedButton';
import ActionDialog from './dialog';

const defaultDialogState = {
  isDialogOpen: false,
  dialogType: null
};

class ActionsRow extends Component {
  state = defaultDialogState;

  openDialog = dialogType => {
    this.setState({
      isDialogOpen: true,
      dialogType
    });
  };

  closeDialog = () => {
    this.setState(defaultDialogState);
  };

  createTransaction = values => {
    this.props
      .createTransaction(values)
      .then(() => {
        this.setState(defaultDialogState);
      })
      .catch(err => {
        console.error(err);
      });
  };

  render() {
    return (
      <Fragment>
        <div style={{ display: 'flex', justifyContent: 'center', flexWrap: 'wrap' }}>
          <RaisedButton
            label="Add Charge"
            onClick={() => {
              this.openDialog('ADD_CHARGE');
            }}
            primary
            style={{ margin: '10px' }}
          />
          <RaisedButton
            label="Add Discount"
            onClick={() => {
              this.openDialog('ADD_DISCOUNT');
            }}
            primary
            style={{ margin: '10px' }}
          />
          <RaisedButton
            label="Add Refund"
            onClick={() => {
              this.openDialog('ADD_REFUND');
            }}
            primary
            style={{ margin: '10px' }}
          />
          <RaisedButton
            label="Make an Adjustment"
            onClick={() => {
              this.openDialog('MAKE_ADJUSTMENT');
            }}
            primary
            style={{ margin: '10px' }}
          />
          <RaisedButton
            label="Receive a Payment"
            onClick={() => {
              this.openDialog('RECEIVE_PAYMENT');
            }}
            primary
            style={{ margin: '10px' }}
          />
        </div>
        <ActionDialog
          openDialog={this.openDialog}
          closeDialog={this.closeDialog}
          createTransaction={this.createTransaction}
          open={this.state.isDialogOpen}
          type={this.state.dialogType}
          idHash={this.props.idHash}
          maxDiscount={this.props.maxDiscount}
          maxRefund={this.props.maxRefund}
        />
      </Fragment>
    );
  }
}

export default ActionsRow;
