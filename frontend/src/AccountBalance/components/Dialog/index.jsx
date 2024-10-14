import React from 'react';
import { dialogTypes } from '../../lib/constants';
import AddCharge from './AddCharge';
import AddDiscount from './AddDiscount';
import AddRefund from './AddRefund';
import AddAdjustment from './AddAdjustment';
import ReceivePayment from './ReceivePayment';
import ConvertToInvoice from './ConvertToInvoice';
import UpdateRemarks from './UpdateRemarks';
import DeleteTransaction from './DeleteTransaction';
import ApprovePendingTransaction from './ApprovePendingTransaction';
import DeletePendingTransaction from './DeletePendingTransaction';

const getDialogComponent = props => {
  const dialogComponents = {
    NONE: <div>No content to display</div>,
    ADD_CHARGE: <AddCharge {...props} />,
    ADD_DISCOUNT: <AddDiscount {...props} />,
    ADD_REFUND: <AddRefund {...props} />,
    ADD_ADJUSTMENT: <AddAdjustment {...props} />,
    RECEIVE_PAYMENT: <ReceivePayment {...props} />,
    CONVERT_TO_INVOICE: <ConvertToInvoice {...props} />,
    CONFIRM_APPROVE_PENDING: <ApprovePendingTransaction {...props} />,
    CONFIRM_DELETE: <DeleteTransaction {...props} />,
    CONFIRM_DELETE_PENDING: <DeletePendingTransaction {...props} />,
    UPDATE_REMARKS: <UpdateRemarks {...props} />
  };

  return dialogComponents[props.type];
};

const Dialog = props => {
  return (
    <div className="modal fade" id="modal" role="dialog">
      <div className="modal-dialog" role="document">
        <div className="modal-content">
          <div className="modal-header">
            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <h4 className="modal-title">{dialogTypes[props.type]}</h4>
          </div>
          {getDialogComponent(props)}
        </div>
      </div>
    </div>
  );
};

export default Dialog;
