import axios from 'axios';
import React, { Component } from 'react';
import { withFormik } from 'formik';
import Yup from 'yup';
import { companyPaymentTxTypes } from '../../../common/companyTransactions';
import { summarizeTransactions } from '../../../common/candidateTransactions';
import { selectTestSession, selectCandidate, selectCandidateByTestSession } from './helpers';
import SearchSection from './SearchSection';
import ResultsSection from './ResultsSection';
import TransactionDetailsSection from './TransactionDetailsSection';

class AddPage extends Component {
  state = {
    results: [],
    testSessionsById: {},
    areResultsLoading: false,
    testSessionRosters: {},
    selectedTestSessions: [],
    selectedCandidatesByTestSession: {}
  };

  searchTestSessions = ({ startDate, endDate, testSiteId }) => {
    this.setState(
      {
        areResultsLoading: true
      },
      async () => {
        try {
          let queryStr = `?startDate=${startDate}&endDate=${endDate}`;

          if (testSiteId) {
            queryStr += `&testSiteId=${testSiteId}`;
          }

          const { data: results } = await axios.get(`/api/test-session/find${queryStr}`);
          const newTestSessionsById = results.reduce(
            (acc, t) => ({
              ...acc,
              [t.id]: t
            }),
            {}
          );

          this.setState({
            results,
            testSessionsById: { ...this.state.testSessionsById, ...newTestSessionsById },
            areResultsLoading: false
          });
        } catch (e) {
          console.error(e);
          this.setState({ results: [], areResultsLoading: false });
        }
      }
    );
  };

  updateTestSessionRoster = (id, rosterInfo) => {
    this.setState({
      testSessionRosters: {
        ...this.state.testSessionRosters,
        [id]: rosterInfo
      }
    });
  };

  fetchRosterInfo = async testSessionId => {
    const { data: { candidates } } = await axios.get(`/api/test-session/roster-info?id=${testSessionId}`);

    const rosterInfo = candidates.map(({ id, name, companyName, transactions }) => {
      const { amountDue } = summarizeTransactions(transactions, false);
      return { id, name, companyName, amountDue };
    });

    this.updateTestSessionRoster(testSessionId, rosterInfo);
  };

  selectCandidate = (candidate, testSessionId) => {
    const { selectedTestSessions, selectedCandidates, selectedCandidatesByTestSession } = this.props.values;

    this.props.setValues({
      ...this.props.values,
      selectedTestSessions: selectTestSession(testSessionId, selectedTestSessions),
      selectedCandidates: selectCandidate(candidate, selectedCandidates),
      selectedCandidatesByTestSession: selectCandidateByTestSession(
        candidate.id,
        testSessionId,
        selectedCandidatesByTestSession
      )
    });
  };

  selectCandidatePerTestSession = testSessionId => {
    const { testSessionsById, testSessionRosters } = this.state;
    const { selectedTestSessions, selectedCandidates, selectedCandidatesByTestSession } = this.props.values;

    const testSession = testSessionsById[testSessionId];

    const result = testSessionRosters[testSessionId].reduce(
      (acc, candidate) => {
        return {
          byCandidate: {
            ...acc.byCandidate,
            [candidate.id]: {
              id: candidate.id,
              name: candidate.name,
              companyName: candidate.companyName,
              testSession: testSession.desc,
              amountToBePaid: candidate.amountDue,
              amountDue: candidate.amountDue
            }
          },
          byTestSession: [...acc.byTestSession, candidate.id]
        };
      },
      {
        byCandidate: {},
        byTestSession: []
      }
    );

    this.props.setValues({
      ...this.props.values,
      selectedTestSessions: selectTestSession(testSessionId, selectedTestSessions),
      selectedCandidates: {
        ...selectedCandidates,
        ...result.byCandidate
      },
      selectedCandidatesByTestSession: {
        ...selectedCandidatesByTestSession,
        [testSessionId]: result.byTestSession
      }
    });
  };

  deselectCandidate = id => {
    const newSelectedCandidates = this.state.selectedCandidates.filter(candidate => candidate.id !== id);

    this.setState({
      selectedCandidates: newSelectedCandidates
    });
  };

  render() {
    const { companiesById, companies, testSites } = this.props;
    const { results, areResultsLoading, testSessionRosters, testSessionsById } = this.state;
    const { selectedTestSessions, selectedCandidates, selectedCandidatesByTestSession } = this.props.values;

    return (
      <div>
        <SearchSection testSites={testSites} searchTestSessions={this.searchTestSessions} />
        <ResultsSection
          results={results}
          areResultsLoading={areResultsLoading}
          testSessionRosters={testSessionRosters}
          updateTestSessionRoster={this.updateTestSessionRoster}
          fetchRosterInfo={this.fetchRosterInfo}
          selectCandidate={this.selectCandidate}
          selectCandidatePerTestSession={this.selectCandidatePerTestSession}
        />
        <TransactionDetailsSection
          companiesById={companiesById}
          companies={companies}
          deselectCandidate={this.deselectCandidate}
          testSessionsById={testSessionsById}
          selectedTestSessions={selectedTestSessions}
          selectedCandidates={selectedCandidates}
          selectedCandidatesByTestSession={selectedCandidatesByTestSession}
          handleSaveBtnClick={this.props.handleSubmit}
        />
      </div>
    );
  }
}

export default withFormik({
  mapPropsToValues: () => ({
    company: '',
    companyId: '',
    type: '',
    checkNumber: '',
    amountReceived: '',
    applyPercentageAdjustment: false,
    percentageAdjustment: '',
    selectedTestSessions: [],
    selectedCandidates: {},
    selectedCandidatesByTestSession: {}
  }),
  handleSubmit: async values => {
    const candidate_transactions = Object.keys(values.selectedCandidates).reduce((acc, id) => {
      const { id: candidateId, amountToBePaid } = values.selectedCandidates[id];
      return [
        ...acc,
        {
          candidate_id: candidateId,
          amount: amountToBePaid
        }
      ];
    }, []);

    const payload = {
      company_id: values.companyId,
      type: values.type,
      amount: values.amountReceived,
      candidate_transactions
    };

    if (values.type === 'PAYMENT_CHECK' && values.checkNumber) {
      payload.check_number = values.checkNumber;
    }

    try {
      await axios.post(`/admin/company/add-transaction`, payload);
      window.location.reload();
    } catch (e) {
      console.error(e);
    }
  },
  validationSchema: Yup.object().shape({
    companyId: Yup.number().required('Company Name is required.'),
    type: Yup.string().oneOf(companyPaymentTxTypes, 'Payment Type is required.').required('Payment Type is required.'),
    amountReceived: Yup.number().required('Amount Received is required.')
  })
})(AddPage);
