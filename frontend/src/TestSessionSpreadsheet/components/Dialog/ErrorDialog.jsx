import React from 'react';
import MUIDialog from 'material-ui/Dialog';
import FlatButton from 'material-ui/FlatButton';

const ErrorDialog = props => {
  const actions = [<FlatButton label="OK" primary onTouchTap={props.closeDialog} />];

  return (
    <MUIDialog title={props.data.title} actions={actions} modal open={props.isOpen}>
      {props.data.body}
    </MUIDialog>
  );
};

export default ErrorDialog;
