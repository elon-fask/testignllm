import React from 'react';

function LoadingPlaceholder() {
  return (
    <div className="loading-placeholder">
      <i className="fa fa-circle-o-notch fa-spin fa-2x fa-fw" />
      <span className="sr-only">Loading...</span>
    </div>
  );
}

export default LoadingPlaceholder;
