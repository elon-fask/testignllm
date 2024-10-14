import React from 'react';
import moment from 'moment';

const Container = ({ children }) => (
  <div style={{ marginBottom: '16px' }}>
    <span>{children}</span>
  </div>
);

const LinkedAccountListItem = props => {
  const isLinked = !!props.linkedAccount;

  if (!isLinked) {
    return (
      <Container>
        <props.logo />
        <button
          type="button"
          onClick={props.handleLinkedAccountButtonClick}
          data-provider={props.provider}
          data-action="LINK"
          className="btn btn-primary"
          style={{ marginLeft: '16px' }}
        >
          Link Account
        </button>
      </Container>
    );
  }

  const isExpired = moment().isAfter(moment(props.linkedAccount.refresh_token_expires_at));

  if (isExpired) {
    return (
      <Container>
        <props.logo />
        <button
          type="button"
          onClick={props.handleLinkedAccountButtonClick}
          data-provider={props.provider}
          data-action="LINK"
          className="btn btn-danger"
          style={{ marginLeft: '16px' }}
        >
          Expired - Re-link Account
        </button>
      </Container>
    );
  }

  return (
    <Container>
      <props.logo />
      <button
        type="button"
        onClick={props.handleLinkedAccountButtonClick}
        data-provider={props.provider}
        data-action="NONE"
        className="btn btn-success"
        style={{ marginLeft: '16px' }}
      >
        Linked
      </button>
    </Container>
  );
};

export default LinkedAccountListItem;
