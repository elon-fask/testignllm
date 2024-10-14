import React, { Component } from 'react';
import Header from './Header';
import Landing from './Landing';
import Registration from './Registration';
import '../styles/index.css';

export default class App extends Component {
  state = {
    companySection: {
      address: '',
      city: '',
      contactEmail: '',
      contactPerson: '',
      fax: '',
      name: '',
      phone: '',
      zip: ''
    },
    customerSection: {
      address: '',
      birthday: '',
      cellNumber: '',
      city: '',
      confirmEmail: '',
      email: '',
      faxNumber: '',
      firstName: '',
      lastName: '',
      middleName: '',
      phone: '',
      suffix: '',
      zip: ''
    },
    landingSection: {
      keyword: ''
    },
    testSessionSection: {
      testSessionId: '',
      testSiteId: ''
    },
    ui: {
      step: 0
    }
  };

  updateSection = (section, values) => {
    this.setState({
      [section]: {
        ...section.values,
        ...values
      }
    });
  };

  handleKeywordFormSubmit = event => {
    event.preventDefault();
    this.setState({
      ui: {
        ...this.state.ui,
        step: 1
      }
    });
  };

  render() {
    let page = null;
    switch (this.state.ui.step) {
      case 0: {
        page = (
          <Landing key={1} handleKeywordFormSubmit={this.handleKeywordFormSubmit} updateSection={this.updateSection} />
        );
        break;
      }
      default: {
        page = <Registration />;
      }
    }

    return [<Header key={0} />, page];
  }
}
