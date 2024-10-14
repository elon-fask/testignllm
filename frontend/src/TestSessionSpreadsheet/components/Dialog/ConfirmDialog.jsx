import React from 'react';
import MUIDialog from 'material-ui/Dialog';
import FlatButton from 'material-ui/FlatButton';
import RaisedButton from 'material-ui/RaisedButton';

const ConfirmDialog = props => {
  const actions = [
    <FlatButton label="Cancel" style={{ marginRight: '20px' }} primary onTouchTap={props.closeDialog} />,
    <RaisedButton
      label="Confirm"
      primary
      onTouchTap={() => {
        props.data.confirm();
        props.closeDialog();
      }}
    />
  ];

  return (
    <MUIDialog title={props.data.title} actions={actions} modal open={props.isOpen}>
      {props.data.body}
    </MUIDialog>
  );
};

export default ConfirmDialog;
