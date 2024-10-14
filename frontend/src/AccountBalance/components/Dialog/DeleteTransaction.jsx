import React from 'react';

const DeleteTransaction = props => [
  <div key={0} className="modal-body">
    <h4>Are you sure you want to delete transaction?</h4>
  </div>,
  <div key={1} className="modal-footer">
    <button type="button" onClick={props.handleCloseDialog} data-dismiss="modal" className="btn btn-default">
      Cancel
    </button>
    <button
      type="button"
      onClick={() => {
        props.deleteTransaction(props.currentTransactionId);
      }}
      className="btn btn-danger"
    >
      Delete
    </button>
  </div>
];

export default DeleteTransaction;
