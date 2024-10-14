import axios from 'axios';
import _sortBy from 'lodash/sortBy';
import React, { Component } from 'react';
import './style.css';
import LoadingPlaceholder from './LoadingPlaceholder';
import TransactionSummary from './TransactionSummary';
import CandidateTransactions from './CandidateTransactions';

class DetailsPage extends Component {
  state = {
    isLoadingTransactionDetails: false,
    transactionDetails: {},
    isLoadingCandidateTx: false,
    candidateTransactions: []
  };

  async componentDidMount() {
    try {
      this.setState({ isLoadingTransactionDetails: true });
      const { data: transactionDetails } = await axios.get(
        `/api/company-transaction/details?id=${this.props.match.params.id}`
      );
      this.setState({ transactionDetails, isLoadingTransactionDetails: false });

      this.setState({ isLoadingCandidateTx: true });
      const { data } = await axios.get(
        `/api/company-transaction/candidate-transactions?id=${this.props.match.params.id}`
      );
      const candidateTransactions = _sortBy(data, ['candidate.name']);
      this.setState({ candidateTransactions, isLoadingCandidateTx: false });
    } catch (e) {
      console.error(e);
    }
  }

  render() {
    return (
      <div className="details-page-container">
        {this.state.isLoadingTransactionDetails ? (
          <LoadingPlaceholder />
        ) : (
          <TransactionSummary transactionDetails={this.state.transactionDetails} />
        )}
        {this.state.isLoadingCandidateTx ? (
          <LoadingPlaceholder />
        ) : (
          <CandidateTransactions candidateTransactions={this.state.candidateTransactions} />
        )}
      </div>
    );
  }
}

export default DetailsPage;
