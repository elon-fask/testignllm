import React, { Component, Fragment } from 'react';
import axios from 'axios';
import { apiDeletePracticalTestSchedule } from '../../common/api';
import { dialogTypeApiMapping } from '../lib/constants';
import ChecklistItem from './ChecklistItem';
import Dialog from './Dialog';
import PracticalTestSchedulePanel from './panels/PracticalTestSchedulePanel';
import CurrentSessionsPanel from './panels/CurrentSessionsPanel';

const getChecklistApiUrl = (candidateId, type) => {
  return `/api/candidates/update-checklist?id=${candidateId}&type=${type}`;
};

class Main extends Component {
  constructor(props) {
    super(props);

    this.state = {
      dialogType: 'NONE',
      dialogDetails: null,
      dialogIsReset: false,
      signedWFormReceived: props.candidate.signed_w_form_received,
      signedPFormReceived: props.candidate.signed_p_form_received,
      confirmationEmailLastSent: props.candidate.confirmation_email_last_sent,
      appFormSentToNccco: props.candidate.app_form_sent_to_nccco
    };
  }

  hydrateState = data => {
    this.setState({
      signedWFormReceived: data.signed_w_form_received,
      signedPFormReceived: data.signed_p_form_received,
      confirmationEmailLastSent: data.confirmation_email_last_sent,
      appFormSentToNccco: data.app_form_sent_to_nccco
    });
  };

  confirmChecklistItem = async (isReset = false, details) => {
    const type = dialogTypeApiMapping[this.state.dialogType];
    const apiUrl = getChecklistApiUrl(this.props.candidate.id, type);

    try {
      if (type === 'confirmation_email_last_sent') {
        const { data } = await axios.post(apiUrl, { isReset, details });
        this.hydrateState(data);

        $('#modal').modal('hide');
        return;
      }
      const { data } = await axios.post(apiUrl, { isReset });
      this.hydrateState(data);
    } catch (e) {
      console.error(e);
    }

    $('#modal').modal('hide');
  };

  deletePracticalTestSchedule = async id => {
    try {
      await apiDeletePracticalTestSchedule(id);
      window.location.reload();
    } catch (err) {
      console.error(err);
    }
  };

  openDialog = (type, details) => {
    this.setState({
      dialogType: type,
      dialogIsReset: false,
      dialogDetails: details
    });
  };

  handleConfirmClick = type => {
    this.setState({
      dialogType: type,
      dialogIsReset: false
    });
  };

  handleConfirmResetClick = type => {
    this.setState({
      dialogType: type,
      dialogIsReset: true
    });
  };

  updatePracticeTimeCredits = credits => {
    axios
      .post(`/api/candidates/update-json?id=${this.props.candidate.id}`, {
        practice_time_credits: credits
      })
      .then(() => {
        window.location.reload();
      })
      .catch(e => {
        console.error(e);
      });
  };

  render() {
    const { signedWFormReceived, signedPFormReceived, confirmationEmailLastSent, appFormSentToNccco } = this.state;

    return (
      <Fragment>
        <div className="panel panel-default">
          <div className="panel-heading">
            <h4>Candidate Checklist</h4>
          </div>
          <div className="panel-body" style={{ display: 'flex', justifyContent: 'space-between' }}>
            <div>
              <ChecklistItem
                heading="Written Form"
                label="Confirm Signed Written Form Received"
                value={signedWFormReceived}
                action={() => {
                  this.handleConfirmClick('SIGNED_W_FORM');
                }}
                actionReset={() => {
                  this.handleConfirmResetClick('SIGNED_W_FORM');
                }}
              />
              <ChecklistItem
                heading="Practical Form"
                label="Confirm Signed Practical Form Received"
                value={signedPFormReceived}
                action={() => {
                  this.handleConfirmClick('SIGNED_P_FORM');
                }}
                actionReset={() => {
                  this.handleConfirmResetClick('SIGNED_P_FORM');
                }}
              />
            </div>
            <div>
              <ChecklistItem
                heading="Confirmation Email"
                label="Send Confirmation Email"
                value={confirmationEmailLastSent}
                action={() => {
                  this.handleConfirmClick('CONFIRM_EMAIL');
                }}
                actionReset={() => {
                  this.handleConfirmResetClick('CONFIRM_EMAIL');
                }}
              />
              <ChecklistItem
                heading="Application Forms Sent to NCCCO"
                label="Confirm Application Forms Sent to NCCCO"
                value={appFormSentToNccco}
                action={() => {
                  this.handleConfirmClick('SENT_TO_NCCCO');
                }}
                actionReset={() => {
                  this.handleConfirmResetClick('SENT_TO_NCCCO');
                }}
              />
            </div>
          </div>
        </div>
        <PracticalTestSchedulePanel
          candidate={this.props.candidate}
          practicalTestSessionId={this.props.practicalTestSessionId}
          schedule={this.props.candidate.practicalTestSchedule}
          openDialog={this.openDialog}
        />
        <CurrentSessionsPanel candidate={this.props.candidate} openDialog={this.openDialog} />
        <Dialog
          type={this.state.dialogType}
          details={this.state.dialogDetails}
          isReset={this.state.dialogIsReset}
          candidate={this.props.candidate}
          practiceTimeCredits={this.props.candidate.practiceTimeCredits}
          updatePracticeTimeCredits={this.updatePracticeTimeCredits}
          confirmChecklistItem={this.confirmChecklistItem}
          deletePracticalTestSchedule={this.deletePracticalTestSchedule}
        />
      </Fragment>
    );
  }
}

export default Main;
