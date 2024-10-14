import React, { Component } from 'react';
import PanelContainer from './PanelContainer';
import SearchForm from './SearchForm';

class SearchSection extends Component {
  state = {
    isHidden: false
  };

  hideSection = () => {
    this.setState({ isHidden: true });
  };

  handleHideToggleBtnClick = () => {
    this.setState({
      isHidden: !this.state.isHidden
    });
  };

  render() {
    const { companiesById, companies, searchTestSessions, testSites } = this.props;

    const panelProps = {
      title: 'Search for Test Sessions',
      isHidden: this.state.isHidden,
      handleHideToggleBtnClick: this.handleHideToggleBtnClick
    };

    return (
      <PanelContainer {...panelProps}>
        <SearchForm
          testSites={testSites}
          searchTestSessions={searchTestSessions}
          hideSection={this.hideSection}
          companiesById={companiesById}
          companies={companies}
        />
      </PanelContainer>
    );
  }
}

export default SearchSection;
