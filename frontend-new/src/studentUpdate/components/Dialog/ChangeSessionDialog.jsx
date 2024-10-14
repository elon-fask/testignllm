import React, { Fragment } from 'react';
import { SESSION_TYPE_PRACTICAL } from '../../../common/testSession';

const ChangeSessionDialog = ({ candidate, details }) => {
  const isPractical = details.sessionType === SESSION_TYPE_PRACTICAL;

  return (
    <Fragment>
      <div className="modal-body">
        <h4>Presets</h4>
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          <a
            className="btn btn-primary"
            href={`/admin/testsession?TestSessionSearch[session_type]=${details.sessionType}&session_type=${
              details.sessionType
            }&candidateId=${candidate.md5}`}
            style={{ marginBottom: '10px' }}
          >
            Reschedule All Tests (Both Written and Practical Test Sessions)
          </a>
          <a
            className="btn btn-primary"
            href={`/admin/testsession?TestSessionSearch[session_type]=${details.sessionType}&session_type=${
              details.sessionType
            }&singleTestSession=1&candidateId=${candidate.md5}`}
            style={{ marginBottom: '10px' }}
          >
            {`Reschedule All Tests (${isPractical ? 'Practical' : 'Written'} Test Session Only)`}
          </a>
          <a
            className="btn btn-primary"
            href={`/admin/testsession?TestSessionSearch[session_type]=${details.sessionType}&session_type=${
              details.sessionType
            }&candidateId=${candidate.md5}&transfer_type=PARTIAL_PAID`}
            style={{ marginBottom: '10px' }}
          >
            Partial Reschedule (Fees Paid in Current Session)
          </a>
          <a
            className="btn btn-primary"
            href={`/admin/testsession?TestSessionSearch[session_type]=${details.sessionType}&session_type=${
              details.sessionType
            }&candidateId=${candidate.md5}&transfer_type=PARTIAL_UNPAID`}
            style={{ marginBottom: '10px' }}
          >
            Partial Reschedule (Transfer Fees to New Session)
          </a>
          <a
            className="btn btn-primary"
            href={`/admin/testsession?TestSessionSearch[session_type]=${details.sessionType}&session_type=${
              details.sessionType
            }&candidateId=${candidate.md5}`}
            style={{ marginBottom: '10px' }}
          >
            Manual Selection
          </a>
        </div>
      </div>
      <div className="modal-footer">
        <button type="button" data-dismiss="modal" className="btn btn-primary">
          Close
        </button>
      </div>
    </Fragment>
  );
};

export default ChangeSessionDialog;
