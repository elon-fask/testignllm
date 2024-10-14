import React, { Component } from 'react';
import PanelContainer from '../PanelContainer';
import FieldsSection from './FieldsSection';
import TransactionSummary from './TransactionSummary';
import TestSessionListing from './TestSessionListing';

class TransactionDetailsSection extends Component {
  state = {
    isHidden: false
  };

  handleHideToggleBtnClick = () => {
    this.setState({
      isHidden: !this.state.isHidden
    });
  };

  handleDeleteBtnClick = e => {
    const { candidateId } = e.currentTarget.dataset;
    this.props.deselectCandidate(parseInt(candidateId, 10));
  };

  render() {
    const panelProps = {
      title: 'Company Transaction Details',
      isHidden: this.state.isHidden,
      handleHideToggleBtnClick: this.handleHideToggleBtnClick
    };

    return (
      <PanelContainer {...panelProps}>
        <form onSubmit={this.props.handleSubmit}>
          <FieldsSection companiesById={this.props.companiesById} companies={this.props.companies} />
          <TransactionSummary />
          {this.props.selectedTestSessions.map(id => {
            const testSession = this.props.testSessionsById[id];
            const candidateIds = this.props.selectedCandidatesByTestSession[id];

            return (
              <TestSessionListing
                key={id}
                testSession={testSession}
                selectedCandidates={this.props.selectedCandidates}
                candidateIds={candidateIds}
                handleDeleteBtnClick={this.handleDeleteBtnClick}
              />
            );
          })}
          <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
            <button type="button" className="btn btn-success" onClick={this.props.handleSaveBtnClick}>
              Save Company Transaction
            </button>
          </div>
        </form>
      </PanelContainer>
    );
  }
}

export default TransactionDetailsSection;
