import React, { Component } from 'react';
import TestSessionCandidatesTable from './TestSessionCandidatesTable';

class TestSessionListing extends Component {
  state = {
    isTableVisible: false
  };

  toggleVisibility = () => {
    this.setState({
      isTableVisible: !this.state.isTableVisible
    });
  };

  render() {
    const toggleBtnText = this.state.isTableVisible ? 'Hide' : 'Show';

    return (
      <div style={{ marginTop: '16px' }}>
        <div style={{ display: 'flex', alignItems: 'center' }}>
          <div style={{ fontWeight: 'bold', fontSize: '1.2em' }}>{this.props.testSession.desc}</div>
          <button
            type="button"
            onClick={this.toggleVisibility}
            className="btn btn-primary"
            style={{ marginLeft: '16px' }}
          >
            {toggleBtnText}
          </button>
        </div>
        {this.state.isTableVisible && (
          <TestSessionCandidatesTable
            selectedCandidates={this.props.selectedCandidates}
            candidateIds={this.props.candidateIds}
            handleDeleteBtnClick={this.props.handleDeleteBtnClick}
          />
        )}
      </div>
    );
  }
}

export default TestSessionListing;
