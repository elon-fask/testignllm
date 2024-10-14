import React from 'react';
import MUIDialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import NavigationCheck from 'material-ui/svg-icons/navigation/check';
import { red500, green500 } from 'material-ui/styles/colors';

const ChecklistItem = ({ value, label, heading, action, actionReset }) => {
  const isChecked = value && value !== '--';
  return (
    <div style={{ marginBottom: '40px' }}>
      <h4>
        {heading} {isChecked && <NavigationCheck color={green500} />}
      </h4>
      {isChecked && <div>{value}</div>}
      <div>
        <RaisedButton style={{ width: '500px', marginRight: '8px' }} label={label} primary onTouchTap={action} />
        <RaisedButton
          label={<i className="fa fa-times" aria-hidden="true" />}
          labelColor="rgb(255, 255, 255)"
          backgroundColor={red500}
          onTouchTap={actionReset}
        />
      </div>
    </div>
  );
};

const CandidateChecklistDialog = props => {
  const actions = [<RaisedButton label="Close" primary onTouchTap={props.closeDialog} />];
  const {
    id,
    signedWFormReceived,
    signedPFormReceived,
    confirmationEmailLastSent,
    appFormSentToNccco
  } = props.candidate;

  return (
    <MUIDialog
      title={`Candidate Checklist - ${props.candidate.name}`}
      actions={actions}
      modal
      open={props.isOpen}
      autoScrollBodyContent
    >
      <div>
        <div style={{ marginTop: '20px' }}>
          <a href={`/admin/candidates/update?id=${props.candidate.idHash}`}>Go to Candidate Application Page</a>
        </div>
        <div>
          <ChecklistItem
            heading="Written Form"
            label="Confirm Signed Written Form Received"
            value={signedWFormReceived}
            action={() => {
              props.updateCandidateChecklist(id, 'signed_w_form_received');
            }}
            actionReset={() => {
              props.updateCandidateChecklist(id, 'signed_w_form_received', true);
            }}
          />
          <ChecklistItem
            heading="Practical Form"
            label="Confirm Signed Practical Form Received"
            value={signedPFormReceived}
            action={() => {
              props.updateCandidateChecklist(id, 'signed_p_form_received');
            }}
            actionReset={() => {
              props.updateCandidateChecklist(id, 'signed_p_form_received', true);
            }}
          />
          <ChecklistItem
            heading="Confirmation Email"
            label="Send Confirmation Email"
            value={confirmationEmailLastSent}
            action={() => {
              props.updateCandidateChecklist(id, 'confirmation_email_last_sent');
            }}
            actionReset={() => {
              props.updateCandidateChecklist(id, 'confirmation_email_last_sent', true);
            }}
          />
          <ChecklistItem
            heading="Application Forms Sent to NCCCO"
            label="Confirm Application Forms Sent to NCCCO"
            value={appFormSentToNccco}
            action={() => {
              props.updateCandidateChecklist(id, 'app_form_sent_to_nccco');
            }}
            actionReset={() => {
              props.updateCandidateChecklist(id, 'app_form_sent_to_nccco', true);
            }}
          />
          <RaisedButton
            label="Mark All as Sent to NCCCO"
            labelColor="rgb(255, 255, 255)"
            backgroundColor={red500}
            onTouchTap={() => {
              props.bulkUpdateCandidateChecklist('app_form_sent_to_nccco');
            }}
          />
        </div>
      </div>
    </MUIDialog>
  );
};

export default CandidateChecklistDialog;
