import React from 'react';
import BaseDialog from '../../common/BaseDialog';
import ConfirmDialog from './ConfirmDialog';
import AddPracticalScheduleDialog from './AddPracticalScheduleDialog';
import SetPracticeTimeCreditsDialog from './SetPracticeTimeCreditsDialog';
import ChangeSessionDialog from './ChangeSessionDialog';
import { dialogTypes } from '../../lib/constants';

const getDialogComponent = props => {
  const dialogComponents = {
    NONE: <div>No content to display</div>,
    SIGNED_W_FORM: <ConfirmDialog {...props} />,
    SIGNED_P_FORM: <ConfirmDialog {...props} />,
    CONFIRM_EMAIL: <ConfirmDialog {...props} />,
    SENT_TO_NCCCO: <ConfirmDialog {...props} />,
    ADD_PRACTICAL_SCHEDULE: <AddPracticalScheduleDialog {...props} />,
    DELETE_PRACTICAL_SCHEDULE: <ConfirmDialog {...props} />,
    SET_PRACTICE_TIME_CREDITS: <SetPracticeTimeCreditsDialog {...props} />,
    CHANGE_SESSION: <ChangeSessionDialog {...props} />
  };

  return dialogComponents[props.type];
};

const Dialog = props => {
  const title = `${props.isReset ? 'Reset ' : ''}${dialogTypes[props.type]}`;

  let style = props.style;

  if (props.type === 'CONFIRM_EMAIL') {
    style = {
      ...style,
      width: 'unset',
      maxWidth: '60%'
    };
  }

  return (
    <BaseDialog id="modal" title={title} style={style}>
      {getDialogComponent(props)}
    </BaseDialog>
  );
};

export default Dialog;
