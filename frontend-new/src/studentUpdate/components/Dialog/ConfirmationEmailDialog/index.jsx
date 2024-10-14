import axios from 'axios';
import React, { Component } from 'react';
import BaseConfirmationForm from './BaseConfirmationForm';
import RegularCandidateForm from './RegularCandidateForm';
import PracticalOnlyCandidateForm from './PracticalOnlyCandidateForm';
import EmailEditor from './EmailEditor';

class ConfirmationEmailDialog extends Component {
  state = {
    isLoading: true,
    baseCandidateData: {}
  };

  componentDidMount = async () => {
    try {
      const { data } = await axios.get(`/api/candidates/confirmation-email-details?id=${this.props.candidate.id}`);
      this.setState({
        baseCandidateData: data,
        isLoading: false
      });
    } catch (e) {
      console.error(e);
      this.setState({
        isLoading: false
      });
    }
  };

  toggleSendEmail = () => {
    this.setState({ sendEmail: !this.state.sendEmail });
  };

  render() {
    if (this.state.isLoading) {
      return <BaseConfirmationForm confirmAction={this.props.confirmAction} />;
    }

    const { baseCandidateData } = this.state;

    const candidate = {
      ...baseCandidateData
    };

    if (baseCandidateData.isPracticalOnly) {
      return (
        <div>
          <PracticalOnlyCandidateForm candidate={candidate} confirmAction={this.props.confirmAction} />
        </div>
      );
    }

    return (
      <div>
        <RegularCandidateForm candidate={candidate} confirmAction={this.props.confirmAction} />
      </div>
    );
  }
}

export default ConfirmationEmailDialog;
