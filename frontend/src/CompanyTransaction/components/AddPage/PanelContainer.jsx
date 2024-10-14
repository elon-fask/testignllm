import React from 'react';

function PanelContainer({ title, isHidden, handleHideToggleBtnClick, children }) {
  return (
    <div className="panel panel-default">
      <div style={{ display: 'flex' }} className="panel-heading">
        <h4 style={{ marginRight: '16px' }}>{title}</h4>
        <button className="btn btn-primary" onClick={handleHideToggleBtnClick}>
          {isHidden ? 'Show' : 'Hide'}
        </button>
      </div>
      <div style={{ display: isHidden ? 'none' : 'block' }} className="panel-body">
        {children}
      </div>
    </div>
  );
}

export default PanelContainer;
