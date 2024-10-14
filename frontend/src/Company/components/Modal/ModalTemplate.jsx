import React from 'react';

function ModalTemplate({ title, children, ...props }) {
  return (
    <div className="modal fade" id="modal" role="dialog" aria-labelledby="modal-title-label">
      <div className="modal-dialog" role="document" style={props.style}>
        <div className="modal-content">
          <div className="modal-header">
            <button
              type="button"
              className="close"
              data-dismiss="modal"
              aria-label="Close"
              onClick={props.handleCloseModalClick}
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 className="modal-title" id="modal-title-label">
              {title}
            </h4>
          </div>
          {children}
        </div>
      </div>
    </div>
  );
}

export default ModalTemplate;
