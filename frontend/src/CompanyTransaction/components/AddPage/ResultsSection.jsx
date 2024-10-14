import React, { Component } from 'react';
import { withFormik, Field } from 'formik';
import PanelContainer from './PanelContainer';
import TestSessionRoster from './TestSessionRoster';
import Spinner from '../../../common/components/bootstrap/Spinner';
import TextField from '../../../common/components/formik/bootstrap/TextField';

class ResultsSection extends Component {
  state = {
    isHidden: false
  };

  handleHideToggleBtnClick = () => {
    this.setState({
      isHidden: !this.state.isHidden
    });
  };

  render() {
    const panelProps = {
      title: 'Test Sessions Rosters/Search Results',
      isHidden: this.state.isHidden,
      handleHideToggleBtnClick: this.handleHideToggleBtnClick
    };

    const {
      results,
      areResultsLoading,
      testSessionRosters,
      fetchRosterInfo,
      values,
      selectCandidate,
      selectCandidatePerTestSession
    } = this.props;

    if (areResultsLoading) {
      return (
        <PanelContainer {...panelProps}>
          <Spinner />
        </PanelContainer>
      );
    }

    if (results.length < 1) {
      return (
        <PanelContainer {...panelProps}>
          <div>No Test Sessions found.</div>
        </PanelContainer>
      );
    }

    return (
      <PanelContainer {...panelProps}>
        <div>
          <div style={{ fontWeight: 'bold' }}>Filter Search Results:</div>
          <form onSubmit={this.props.handleSubmit} style={{ display: 'flex', width: '100%' }}>
            <Field
              name="name"
              label="Candidate Name"
              component={TextField}
              style={{ flexGrow: 1, marginRight: '16px' }}
            />
            <Field
              name="companyName"
              label="Company Name"
              component={TextField}
              style={{ flexGrow: 1, marginRight: '16px' }}
            />
          </form>
        </div>
        {results.map(testSession => {
          const rosterInfo = testSessionRosters[testSession.id];

          return (
            <TestSessionRoster
              key={testSession.id}
              testSession={testSession}
              rosterInfo={rosterInfo}
              fetchRosterInfo={fetchRosterInfo}
              nameFilter={values.name}
              companyNameFilter={values.companyName}
              selectCandidate={selectCandidate}
              selectCandidatePerTestSession={selectCandidatePerTestSession}
            />
          );
        })}
      </PanelContainer>
    );
  }
}

export default withFormik({
  mapPropsToValues: () => ({
    name: '',
    companyName: ''
  })
})(ResultsSection);
