import React, { Component, Fragment } from 'react';
import { MemoryRouter as Router, Route, Switch } from 'react-router-dom';
import Nav from './Nav';
import IndexPage from './IndexPage';
import AddPage from './AddPage';
import DetailsPage from './DetailsPage';
import ImportPage from './ImportPage';
import '../styles/Main.css';

class Main extends Component {
  state = {
    companiesById: this.props.companies.map(({ id }) => id),
    companies: this.props.companies.reduce(
      (acc, company) => ({
        ...acc,
        [company.id]: company
      }),
      {}
    ),
    transactionsById: this.props.transactions.map(({ id }) => id),
    transactions: this.props.transactions.reduce(
      (acc, transaction) => ({
        ...acc,
        [transaction.id]: transaction
      }),
      {}
    )
  };

  render() {
    const { companiesById, companies, transactionsById, transactions } = this.state;

    return (
      <Router>
        <Fragment>
          <Nav />
          <Switch>
            <Route
              path="/"
              exact
              render={() => (
                <IndexPage
                  transactionsById={transactionsById}
                  transactions={transactions}
                  companiesById={companiesById}
                  companies={companies}
                />
              )}
            />
            <Route path="/details/:id" render={props => <DetailsPage {...props} />} />
            <Route
              path="/add"
              render={() => (
                <AddPage testSites={this.props.testSites} companiesById={companiesById} companies={companies} />
              )}
            />
            <Route path="/import" render={() => <ImportPage />} />
          </Switch>
        </Fragment>
      </Router>
    );
  }
}

export default Main;
