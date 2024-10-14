import moment from 'moment'

import { apiUpdateCandidate } from '../lib/api';
import {
  apiSetNcccoTestFeesCredit,
  apiUpdatePracticalTestSchedule,
  apiAddTestSessionDays,
  apiDeletePracticalTestSchedule,
  apiResetGrades
} from '../../common/api';
import downloadEntireSpreadsheetImport from './reports/entireSpreadsheet';
import ACTION_TYPES from './actionTypes';

export { updateCandidateChecklist, bulkUpdateCandidateChecklist } from './candidateChecklist';
export {
  createTransaction,
  updateTransactionRemarks,
  deleteTransaction,
  approvePendingTransaction,
  autoAdjustAccountBalance
} from './candidatePayment';
export { openDialog, closeDialog, focusCell, cancelFocusCell, blurCell, setView, setVisibleColumns } from './ui';

export const downloadEntireSpreadsheet = downloadEntireSpreadsheetImport;
export const actionTypes = ACTION_TYPES;

export const setNcccoTestFeesCredit = amount => (dispatch, getState) => {
  return new Promise((resolve, reject) => {
    const { testSession } = getState();

    apiSetNcccoTestFeesCredit(testSession.id, amount)
      .then(() => {
        dispatch({
          type: actionTypes.SET_NCCCO_TEST_FEES_CREDIT,
          payload: amount
        });
        resolve();
      })
      .catch(e => {
        reject(e);
      });
  });
};

export const updatePracticalTestSchedule = payload => async (dispatch, getState) => {
  const { testSession } = getState();
    const startDate = moment(testSession.startDate);
    const endDate = moment(testSession.endDate);
    const numDays = endDate.diff(startDate, 'days') + 1;
    const day = parseInt(payload.day,10);
    try{
      if(day > numDays) {
        const res = await apiAddTestSessionDays(testSession.id, { days: day - numDays  })
        window.testSession.name = res.data;
        document.getElementById('test-session-title').innerText = window.testSession.name;
      }
    const { data } = await apiUpdatePracticalTestSchedule(testSession.id, payload);
    dispatch({
      type: actionTypes.UPDATE_PRACTICAL_TEST_SCHEDULE,
      payload: data
    });
    return 
    } catch(e) {
      throw  e
    }
    
};

export const deletePracticalTestSchedule = id => async (dispatch) => {
  try{
    const { data } = await apiDeletePracticalTestSchedule(id);
    window.testSession.name = data.new_date;
    document.getElementById('test-session-title').innerText = window.testSession.name;
    dispatch({
      type: actionTypes.DELETE_PRACTICAL_TEST_SCHEDULE,
      payload: id
    });
  }catch(e) {
    console.log(e);
  }
};

export const toggleCollectPaymentOverride = candidateId => (dispatch, getState) => {
  const { candidates } = getState();
  const candidate = candidates[candidateId];

  return new Promise((resolve, reject) => {
    apiUpdateCandidate(candidate.id, { collect_payment_override: !candidate.collectPaymentOverride })
      .then(() => {
        dispatch({
          type: actionTypes.UPDATE_CANDIDATE,
          candidateID: candidate.id,
          payload: {
            collectPaymentOverride: !candidate.collectPaymentOverride
          }
        });
        resolve();
      })
      .catch(e => {
        reject(e);
      });
  });
};

export const resetCandidateGrades = candidateId => (dispatch, getState) => {
  const { testSession: { id: testSessionId } } = getState();

  return new Promise((resolve, reject) => {
    apiResetGrades(candidateId, testSessionId)
      .then(() => {
        dispatch({
          type: actionTypes.UPDATE_CANDIDATE,
          candidateID: candidateId,
          payload: {
            grades: {}
          }
        });
        resolve();
      })
      .catch(e => {
        reject(e);
      });
  });
};
