import moment from 'moment';
import ACTION_TYPES from './actionTypes';
import { apiUpdateCandidateChecklist, apiBulkUpdateCandidateChecklist } from '../lib/api';

const processChecklistData = data => {
  const signedWFormReceived = data.signed_w_form_received
    ? moment(data.signed_w_form_received, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
    : '--';

  const signedPFormReceived = data.signed_p_form_received
    ? moment(data.signed_p_form_received, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
    : '--';

  const confirmationEmailLastSent = data.confirmation_email_last_sent
    ? moment(data.confirmation_email_last_sent, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
    : '--';

  const appFormSentToNccco = data.app_form_sent_to_nccco
    ? moment(data.app_form_sent_to_nccco, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY')
    : '--';

  return {
    signedWFormReceived,
    signedPFormReceived,
    confirmationEmailLastSent,
    appFormSentToNccco
  };
};

export const updateCandidateChecklist = (id, type, isReset = false) => dispatch => {
  apiUpdateCandidateChecklist(id, type, isReset).then(({ data }) => {
    dispatch({
      type: ACTION_TYPES.UPDATE_CANDIDATE,
      candidateID: id,
      payload: processChecklistData(data)
    });
  });
};

export const bulkUpdateCandidateChecklist = type => (dispatch, getState) => {
  const { candidateIDs } = getState();

  apiBulkUpdateCandidateChecklist(type, candidateIDs)
    .then(({ data }) => {
      Object.keys(data).forEach(id => {
        const candidateData = data[id];
        dispatch({
          type: ACTION_TYPES.UPDATE_CANDIDATE,
          candidateID: candidateData.id,
          payload: processChecklistData(candidateData)
        });
      });
    })
    .catch(e => {
      console.error(e);
    });
};
