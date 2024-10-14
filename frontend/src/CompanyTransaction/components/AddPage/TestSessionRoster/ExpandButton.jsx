import React, { Fragment } from 'react';

function ExpandButton({ shouldShowAddAll, isLoading, isHidden, handleExpandBtnClick, handleAddAllBtnClick }) {
  if (isLoading) {
    return (
      <button type="button" className="btn btn-primary">
        <i className="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
      </button>
    );
  }

  return (
    <Fragment>
      <button
        type="button"
        className="btn btn-primary"
        onClick={handleExpandBtnClick}
        style={{ minWidth: '110px', marginRight: '16px' }}
      >
        {isHidden ? 'Show Roster' : 'Hide Roster'}
      </button>
      {shouldShowAddAll && (
        <button type="button" className="btn btn-danger" onClick={handleAddAllBtnClick}>
          <i aria-hidden className="fa fa-plus" /> Add All
        </button>
      )}
    </Fragment>
  );
}

export default ExpandButton;
