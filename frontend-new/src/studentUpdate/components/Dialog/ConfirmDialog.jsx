import React, { Fragment } from 'react';
import ConfirmationEmailDialog from './ConfirmationEmailDialog';

const DialogTemplate = props => (
  <Fragment>
    <div className="modal-body">{props.message}</div>
    <div className="modal-footer">
      <button type="button" data-dismiss="modal" className="btn btn-default">
        Close
      </button>
      <button type="button" onClick={props.confirmAction} className="btn btn-success">
        Confirm
      </button>
    </div>
  </Fragment>
);

const ConfirmDialog = props => {
  if (props.type === 'DELETE_PRACTICAL_SCHEDULE') {
    return (
      <DialogTemplate
        message="Are you sure you want to delete practical test schedule?"
        confirmAction={() => {
          props.deletePracticalTestSchedule(props.details.id);
        }}
      />
    );
  }

  if (props.isReset) {
    return (
      <DialogTemplate
        message="Are you sure you want to reset checklist item?"
        confirmAction={() => {
          props.confirmChecklistItem(true);
        }}
      />
    );
  }

  if (props.type === 'CONFIRM_EMAIL') {
    return <ConfirmationEmailDialog candidate={props.candidate} confirmAction={props.confirmChecklistItem} />;
  }

  return (
    <DialogTemplate
      message="Are you sure you want to confirm checklist item?"
      confirmAction={() => {
        props.confirmChecklistItem(false);
      }}
    />
  );
};

export default ConfirmDialog;
