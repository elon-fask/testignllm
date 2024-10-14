import axios from 'axios';
import React, { Component } from 'react';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import SearchSection from './SearchSection';
import ResultsSection from './ResultsSection';

const theme = getMuiTheme({
  palette: {
    accent1Color: '#0471af',
    primary1Color: '#0471af'
  }
});

class App extends Component {
  state = {
    results: []
  };

  handleSearch = queryObj => {
    axios.post('/api/candidates/search', queryObj).then(({ data }) => {
      this.setState({
        results: data
      });
    });
  };

  handleDownloadResults = () => {
    const resultRows = this.state.results.map(result => [result.name, result.company, result.email]);

    axios
      .post('/admin/testsession/render-spreadsheet', {
        data: [['Name', 'Company', 'Email'], ...resultRows],
        filename: 'candidate_search_results.xlsx',
        wsName: 'Candidate Search Results',
        styles: []
      })
      .then(({ data }) => {
        window.location.href = data.link;
      })
      .catch(e => {
        console.error(e);
      });
  };

  render() {
    return (
      <MuiThemeProvider muiTheme={theme}>
        <div>
          <SearchSection handleSearch={this.handleSearch} />
          <ResultsSection results={this.state.results} handleDownloadResults={this.handleDownloadResults} />
        </div>
      </MuiThemeProvider>
    );
  }
}

export default App;
