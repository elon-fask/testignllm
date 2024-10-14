import React, { Fragment } from 'react';

/* eslint-disable jsx-a11y/anchor-is-valid */
/* eslint-disable no-script-url */
const SessionDetails = ({ heading, candidateId, candidateMd5, session, sessionType, openDialog, ...props }) => {
  let descriptionSection = 'Not Enrolled';

  if (session) {
    descriptionSection = (
      <a href={`/admin/testsession/spreadsheet?id=${session.id}`} target="_blank">
        {session.description}
        {session.passed && <i className="fa fa-check pull-right text-success" style={{ fontSize: '24px' }} />}
      </a>
    );
  }

  return (
    <Fragment>
      <h5>{heading}</h5>
      <ul className="list-group" style={{ marginLeft: '25px' }}>
        <li className="list-group-item">
          <div className="remove-session-section clearfix">{descriptionSection}</div>
          <div style={{ marginTop: '10px', display: 'flex', justifyContent: 'space-between' }}>
            <div>
              {props.declinedFx && (
                <div className="btn btn-danger" style={{ marginRight: '1em' }}>
                  Declined FX
                </div>
              )}
              {props.declinedSw && <div className="btn btn-danger">Declined SW</div>}
            </div>
            <div>
              {!session && (
                <a
                  className="btn btn-primary select-session btn-sm"
                  data-candidate-id={candidateId}
                  data-md5-candidate-id={candidateMd5}
                  data-type={sessionType}
                  href="javascript: void(0);"
                  style={{ marginRight: '8px' }}
                >
                  Select Session
                </a>
              )}
              {session && !session.passed && (
                <button
                  className="btn btn-primary btn-sm"
                  data-toggle="modal"
                  data-target="#modal"
                  style={{ marginRight: '8px' }}
                  type="button"
                  onClick={() => {
                    openDialog('CHANGE_SESSION', { sessionType });
                  }}
                >
                  Change Session
                </button>
              )}
              {session && (
                <Fragment>
                  <a
                    href="javascript: void(0);"
                    className="btn btn-danger btn-sm remove-from-session"
                    data-session-id={session.id}
                    data-candidate-id={candidateId}
                    style={{ marginRight: '8px' }}
                  >
                    Cancel session
                  </a>
                  {session.graded ? (
                    <a
                      className="btn btn-danger schedule-retake btn-sm"
                      data-candidate-id={candidateId}
                      data-md5-candidate-id={candidateMd5}
                      data-type={sessionType}
                      href="javascript: void(0);"
                    >
                      Schedule Retake
                    </a>
                  ) : (
                    <a
                      className="btn btn-success grade-a-session btn-sm"
                      data-candidate-id={candidateId}
                      data-md5-candidate-id={candidateMd5}
                      data-type={sessionType}
                      data-test-session-id={session.id}
                      href="javascript: void(0);"
                    >
                      Grade Session
                    </a>
                  )}
                </Fragment>
              )}
            </div>
          </div>
        </li>
      </ul>
    </Fragment>
  );
};

export default SessionDetails;
