import React from 'react';
import SessionDetails from './CurrentSessionsPanel/SessionDetails';

const CurrentSessionsPanel = props => {
  const { declinedTests, practicalTestSession } = props.candidate;

  const checkDeclinedTest = type =>
    declinedTests.reduce((acc, att) => {
      if (acc) {
        return acc;
      }

      if (att.crane === type && practicalTestSession.id === att.test_session_id) {
        return true;
      }

      return acc;
    }, false);

  const declinedFx = checkDeclinedTest('fx');
  const declinedSw = checkDeclinedTest('sw');

  return (
    <div className="panel panel-default">
      <div className="panel-heading">
        <h4>Current Student Sessions</h4>
      </div>
      <div className="panel-body">
        <SessionDetails
          heading="Written Session"
          candidateId={props.candidate.id}
          candidateMd5={props.candidate.md5}
          session={props.candidate.writtenTestSession}
          openDialog={props.openDialog}
          sessionType={2}
        />
        <SessionDetails
          heading="Practical Session"
          candidateId={props.candidate.id}
          candidateMd5={props.candidate.md5}
          session={practicalTestSession}
          declinedFx={declinedFx}
          declinedSw={declinedSw}
          openDialog={props.openDialog}
          sessionType={1}
        />
      </div>
    </div>
  );
};

export default CurrentSessionsPanel;
