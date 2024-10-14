import React from 'react';

const SubmitMessage = props => {
  if (props.status === 'failure') {
    return (
      <article className="message animated fadeIn is-danger">
        <div className="message-header">
          <p>Error</p>
        </div>
        <div className="message-body">
          Travel Form submission failed. The server encountered an error. Please try again.
        </div>
      </article>
    );
  }

  return (
    <article className="message animated fadeIn is-success">
      <div className="message-header">
        <p>Success</p>
      </div>
      <div className="message-body">Travel Form submission successful.</div>
    </article>
  );
};

export default SubmitMessage;
