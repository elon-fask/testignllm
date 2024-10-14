import React from 'react';
import moment from 'moment';

const CheckMark = () => <i className="fa fa-check" style={{ color: 'green', fontSize: '24px' }} />;

const ChecklistItem = ({ heading, label, value, action, actionReset }) => {
  const valueM = moment(value, 'YYYY-MM-DD HH:mm:ss');
  const valueDuration = valueM.format('M/D/YYYY h:mm:ss A');

  return (
    <div style={{ marginBottom: '30px' }}>
      <h5 style={{ fontWeight: 'bold' }}>
        <span>
          {heading} {value && <CheckMark />}
        </span>
      </h5>
      <div>
        <button
          onClick={action}
          data-toggle="modal"
          data-target="#modal"
          style={{ width: '310px', marginRight: '8px' }}
          type="button"
          className="btn btn-primary"
        >
          {label}
        </button>
        <button onClick={actionReset} data-toggle="modal" data-target="#modal" type="button" className="btn btn-danger">
          <i className="fa fa-times" aria-hidden="true" />
        </button>
      </div>
      {value && <div>{valueDuration} PST</div>}
    </div>
  );
};

export default ChecklistItem;
