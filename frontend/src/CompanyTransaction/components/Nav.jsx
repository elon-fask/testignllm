import React, { Component, Fragment } from 'react';
import { Route, withRouter } from 'react-router-dom';

class Nav extends Component {
  handleNavBtnClick = route => () => {
    this.props.history.push(route);
  };

  render() {
    return (
      <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px' }}>
        <h4 style={{ fontWeight: 'bold' }}>Company Transactions</h4>
        <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
          <Route
            exact
            path="/"
            render={() => (
              <Fragment>
                <button
                  type="button"
                  className="btn btn-primary"
                  style={{ marginRight: '16px' }}
                  onClick={this.handleNavBtnClick('/import')}
                >
                  <i className="fa fa-cloud-download" aria-hidden="true" /> Import Company Transactions
                </button>
                <button type="button" className="btn btn-primary" onClick={this.handleNavBtnClick('/add')}>
                  <i className="fa fa-plus" aria-hidden="true" /> Add Company Transaction
                </button>
              </Fragment>
            )}
          />
          <Route
            path="/:path+"
            render={() => (
              <button type="button" className="btn btn-primary" onClick={this.handleNavBtnClick('/')}>
                <i className="fa fa-arrow-left" aria-hidden="true" /> Back
              </button>
            )}
          />
        </div>
      </div>
    );
  }
}

export default withRouter(Nav);
