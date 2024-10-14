import React from 'react';

const TestSessionTitle = props => (
  <div style={{ display: 'flex', justifyContent: 'center' }}>
    <h3 id='test-session-title' style={{ marginTop: 0 }}>{props.title}</h3>
  </div>
);

export default TestSessionTitle;
