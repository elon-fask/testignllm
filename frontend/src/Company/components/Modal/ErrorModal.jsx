import React from 'react';
import ModalTemplate from './ModalTemplate';

function ErrorModal(props) {
  return (
    <ModalTemplate title="Error" handleCloseModalClick={props.handleCloseModalClick}>
      <div className="modal-body">...</div>
      <div className="modal-footer">
        <button type="button" className="btn btn-default" data-dismiss="modal" onClick={props.handleCloseModalClick}>
          Close
        </button>
      </div>
    </ModalTemplate>
  );
}

export default ErrorModal;
