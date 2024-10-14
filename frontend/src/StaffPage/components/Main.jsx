import React, { Component, Fragment } from 'react';
import { linkedAccountProviders } from '../../common/user';
import LinkedAccountListItem from './LinkedAccountListItem';
import QuickBooksLogo from './ui/QuickBooksLogo';

const providerLogoMap = {
  QUICKBOOKS_ONLINE: QuickBooksLogo
};

class Main extends Component {
  handleLinkedAccountButtonClick = e => {
    if (this.props.user.id === this.props.loggedInUserId) {
      const { provider, action } = e.currentTarget.dataset;

      if (action === 'LINK') {
        window.location.href = `/admin/oauth2/link?provider=${provider}`;
      }
    }
  };

  render() {
    const linkedAccountMap = this.props.user.linkedAccounts.reduce(
      (acc, linkedAccount) => ({
        [linkedAccount.provider]: linkedAccount,
        ...acc
      }),
      {}
    );

    return (
      <Fragment>
        <div className="form-group" style={{ marginTop: '24px', display: 'flex' }}>
          <div className="col-xs-4 control-label">Linked Accounts</div>
          <div>
            {linkedAccountProviders.map(provider => (
              <LinkedAccountListItem
                key={provider}
                provider={provider}
                logo={providerLogoMap[provider]}
                linkedAccount={linkedAccountMap[provider]}
                handleLinkedAccountButtonClick={this.handleLinkedAccountButtonClick}
              />
            ))}
          </div>
        </div>
      </Fragment>
    );
  }
}

export default Main;
