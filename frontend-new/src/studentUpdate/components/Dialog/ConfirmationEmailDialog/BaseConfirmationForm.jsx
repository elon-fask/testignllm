import React from 'react';
import CommonFormElements from './CommonFormElements';
import Spinner from '../../../../common/components/Spinner';

function BaseConfirmationForm(props) {
  return (
    <CommonFormElements
      confirmAction={() => {
        props.confirmAction(false, false);
      }}
    >
      <Spinner />
    </CommonFormElements>
  );
}

export default BaseConfirmationForm;
