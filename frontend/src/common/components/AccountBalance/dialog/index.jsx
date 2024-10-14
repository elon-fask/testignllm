import React from 'react';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import AddChargeDialog from './AddChargeDialog';
import AddDiscountDialog from './AddDiscountDialog';
import AddRefundDialog from './AddRefundDialog';
import MakeAdjustmentDialog from './MakeAdjustmentDialog';
import ReceivePaymentDialog from './ReceivePaymentDialog';

const ActionDialog = props => {
  switch (props.type) {
    case 'ADD_CHARGE': {
      return <AddChargeDialog {...props} />;
    }
    case 'ADD_DISCOUNT': {
      return <AddDiscountDialog {...props} />;
    }
    case 'ADD_REFUND': {
      return <AddRefundDialog {...props} />;
    }
    case 'MAKE_ADJUSTMENT': {
      return <MakeAdjustmentDialog {...props} />;
    }
    case 'RECEIVE_PAYMENT': {
      return <ReceivePaymentDialog {...props} />;
    }
    default: {
      return (
        <Dialog
          title="Error"
          actions={[<RaisedButton label="Close" onClick={props.closeDialog} primary />]}
          modal
          open={props.open}
        >
          Invalid transaction type selected.
        </Dialog>
      );
    }
  }
};

export default ActionDialog;
