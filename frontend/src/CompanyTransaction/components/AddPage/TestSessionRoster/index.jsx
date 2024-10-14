import React, { Component } from 'react';
import ExpandButton from './ExpandButton';
import Roster from './Roster';
import { checkIfNameMatches } from './helpers';

class TestSessionRoster extends Component {
  state = {
    isHidden: true,
    isLoading: false
  };

  componentDidMount() {
    this.setState(
      {
        isHidden: true,
        isLoading: true
      },
      async () => {
        await this.props.fetchRosterInfo(this.props.testSession.id);
        this.setState({ isHidden: true, isLoading: false });
      }
    );
  }

  get filteredRoster() {
    const { rosterInfo, nameFilter, companyNameFilter } = this.props;

    if (!rosterInfo) {
      return [];
    }

    return rosterInfo.filter(candidate => {
      const hasAmountDue = candidate.amountDue > 0;
      const nameMatches = checkIfNameMatches(candidate.name, nameFilter);
      const companyNameMatches = checkIfNameMatches(candidate.companyName, companyNameFilter);

      return hasAmountDue && nameMatches && companyNameMatches;
    });
  }

  handleExpandBtnClick = () => {
    if (this.state.isHidden && this.props.rosterInfo) {
      this.setState({
        isHidden: false,
        isLoading: false
      });
    } else if (this.props.rosterInfo) {
      this.setState({
        isHidden: true,
        isLoading: false
      });
    } else {
      this.setState(
        {
          isHidden: true,
          isLoading: true
        },
        async () => {
          await this.props.fetchRosterInfo(this.props.testSession.id);
          this.setState({ isHidden: false, isLoading: false });
        }
      );
    }
  };

  handleAddBtnClick = e => {
    const { candidateId } = e.currentTarget.dataset;
    const candidate = this.props.rosterInfo.find(({ id }) => parseInt(candidateId, 10) === id);

    this.props.selectCandidate(
      {
        id: candidate.id,
        name: candidate.name,
        companyName: candidate.companyName,
        testSession: this.props.testSession.desc,
        amountDue: candidate.amountDue
      },
      this.props.testSession.id
    );
  };

  handleAddAllBtnClick = () => {
    this.props.selectCandidatePerTestSession(this.props.testSession.id);
  };

  render() {
    const { testSession, rosterInfo } = this.props;
    const { handleExpandBtnClick, handleAddBtnClick, handleAddAllBtnClick, filteredRoster } = this;
    const { isLoading, isHidden } = this.state;
    const shouldShowAddAll = !!rosterInfo;

    const expandButtonProps = { shouldShowAddAll, isLoading, isHidden, handleExpandBtnClick, handleAddAllBtnClick };
    const rosterProps = { isHidden, filteredRoster, handleAddBtnClick };

    return (
      <div style={{ marginBottom: '16px' }}>
        <div style={{ display: 'flex', alignItems: 'center' }}>
          <div style={{ fontWeight: 'bold', marginRight: '16px', minWidth: '400px' }}>{testSession.desc}</div>
          <ExpandButton {...expandButtonProps} />
        </div>
        <Roster {...rosterProps} />
      </div>
    );
  }
}

export default TestSessionRoster;
