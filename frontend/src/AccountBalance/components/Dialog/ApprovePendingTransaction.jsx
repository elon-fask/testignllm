import React from 'react';

const ApprovePendingTransaction = props => [
  <div key={0} className="modal-body">
    <h4>Are you sure you want to approve pending transaction?</h4>
  </div>,
  <div key={1} className="modal-footer">
    <button type="button" onClick={props.handleCloseDialog} data-dismiss="modal" className="btn btn-default">
      Cancel
    </button>
    <button type="button" onClick={props.approvePendingTransaction} className="btn btn-success">
      Approve
    </button>
  </div>
];

export default ApprovePendingTransaction;
