import React from 'react';

const TestSessionInfoSection = ({ testSession }) => (
  <div style={{ margin: '20px', display: 'flex', justifyContent: 'center' }}>
    <div style={{ fontWeight: 'bold', marginRight: '20px' }}>
      Test Site Name <br />
      Test Site Coordinator <br />
      Test Site Address <br />
      Test Date <br />
    </div>
    <div style={{ marginRight: '20px' }}>
      {testSession.testSiteName} <br />
      {testSession.testSiteCoordinator} <br />
      {testSession.testSiteAddress} <br />
      {testSession.testingDate} <br />
    </div>
    <div style={{ fontWeight: 'bold', marginRight: '20px' }}>
      Instructor <br />
      Practical Examiner <br />
      Written Administrator <br />
      Practical Test Site Code <br />
    </div>
    <div style={{ marginRight: '20px' }}>
      {testSession.instructor} <br />
      {testSession.practicalExaminer} <br />
      {testSession.testSiteCoordinator} <br />
      {testSession.practicalTestSiteCode} <br />
    </div>
  </div>
);

export default TestSessionInfoSection;
