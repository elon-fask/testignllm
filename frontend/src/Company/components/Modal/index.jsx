import React from 'react';
import ManualAddModal from './ManualAddModal';
import UpdateModal from './UpdateModal';
import ImportFileModal from './ImportFileModal';
import ImportQboModal from './ImportQboModal';
import ErrorModal from './ErrorModal';

const Modal = props => {
  if (props.type === 'MANUAL_ADD') {
    return <ManualAddModal {...props} />;
  }

  if (props.type === 'UPDATE') {
    return <UpdateModal {...props} />;
  }

  if (props.type === 'IMPORT_FILE') {
    return <ImportFileModal {...props} />;
  }

  if (props.type === 'IMPORT_QBO') {
    return <ImportQboModal {...props} />;
  }

  return <ErrorModal {...props} />;
};

export default Modal;
