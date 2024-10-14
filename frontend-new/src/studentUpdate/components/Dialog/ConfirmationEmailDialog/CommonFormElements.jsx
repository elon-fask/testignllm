import React, { Fragment } from 'react';

function CommonFormElements(props) {
  return (
    <Fragment>
      <div className="modal-body">
        <div>Are you sure you want to confirm checklist item?</div>
        {props.children}
      </div>
      <div className="modal-footer">
        <button type="button" data-dismiss="modal" className="btn btn-default">
          Close
        </button>
        <button
          type="button"
          onClick={props.confirmAction}
          disabled={props.confirmDisabled}
          className="btn btn-success"
        >
          Confirm
        </button>
      </div>
    </Fragment>
  );
}

export default CommonFormElements;
