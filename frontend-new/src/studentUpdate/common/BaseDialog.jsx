import React from 'react';

const BaseDialog = props => (
  <div className="modal fade" id={props.id} role="dialog" data-backdrop="static">
    <div className="modal-dialog" role="document" style={props.style}>
      <div className="modal-content">
        <div className="modal-header">
          <button type="button" className="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
          <h4 className="modal-title">{props.title}</h4>
        </div>
        {props.children}
      </div>
    </div>
  </div>
);

export default BaseDialog;
