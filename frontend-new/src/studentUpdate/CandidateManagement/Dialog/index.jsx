import React from 'react';
import BaseDialog from '../../common/BaseDialog';
import GenerateCertificateDialog from './GenerateCertificateDialog';

const getDialogComponent = props => {
  return <GenerateCertificateDialog {...props} />;
};

const getTitle = type => {
  return 'Generate Certificate';
};

const Dialog = props => (
  <BaseDialog id="modal-candidate-mgmt" title={getTitle(props.type)}>
    {getDialogComponent(props)}
  </BaseDialog>
);

export default Dialog;
