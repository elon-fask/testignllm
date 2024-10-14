import React from 'react';

function Spinner({ style }) {
  return (
    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', ...style }}>
      <i className="fa fa-circle-o-notch fa-spin fa-3x fa-fw" />
      <span className="sr-only">Loading...</span>
    </div>
  );
}

export default Spinner;
