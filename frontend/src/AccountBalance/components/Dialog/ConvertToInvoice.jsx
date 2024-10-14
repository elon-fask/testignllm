import React, { Fragment } from 'react';
import { formatMoney } from 'accounting';
import { summarizeTransactions } from '../../../common/candidateTransactions';

const ConvertToInvoice = props => {
  const { customerCharges, amountPaid } = summarizeTransactions(props.candidate.transactions, false);
  const isValid = !(customerCharges < 295) && amountPaid > 295;

  if (isValid) {
    const refundAmount = amountPaid - 295;

    return (
      <Fragment>
        <div className="modal-body">
          <div>The following transactions will be added:</div>
          <table className="table table-condensed table-striped">
            <thead>
              <tr>
                <th>Type</th>
                <th>Amount</th>
                <th />
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Refund</td>
                <td>{formatMoney(refundAmount)}</td>
              </tr>
              <tr>
                <td>Charge - Application</td>
                <td>{formatMoney(refundAmount)}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div className="modal-footer">
          <button type="button" data-dismiss="modal" className="btn btn-default">
            Close
          </button>
          {isValid && (
            <button type="button" onClick={props.convertToInvoice} className="btn btn-success">
              Confirm
            </button>
          )}
        </div>
      </Fragment>
    );
  }

  return (
    <Fragment>
      <div className="modal-body">
        {`Total charges or total amount paid is less than ${formatMoney(
          295
        )}, account balance cannot be converted to invoice payment scheme.`}
      </div>
      <div className="modal-footer">
        <button type="button" data-dismiss="modal" className="btn btn-default">
          Close
        </button>
      </div>
    </Fragment>
  );
};

export default ConvertToInvoice;
