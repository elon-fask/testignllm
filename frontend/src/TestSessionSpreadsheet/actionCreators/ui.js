import { dialogTypes } from '../reducers/ui';
import { validate, errorMessages } from '../lib/validators';
import { apiUpdateCandidateField } from '../lib/api';
import { generateDispatchObject, prepareApiValue, COL_NUM_MAPPING } from '../lib/refs';
import ACTION_TYPES from './actionTypes';

export const openDialog = (dialogType = dialogTypes.ERROR, data = {}) => dispatch => {
  dispatch({
    dialogType,
    type: ACTION_TYPES.OPEN_DIALOG,
    data
  });
};

export const closeDialog = () => ({
  type: ACTION_TYPES.CLOSE_DIALOG
});

export const focusCell = (row, col, table, candidateID) => (dispatch, getState) => {
  const state = getState();
  const ID = candidateID || state.candidateIDs[row];
  const candidate = state.candidates[ID];

  switch (col) {
    case COL_NUM_MAPPING.applicationType: {
      dispatch({
        type: ACTION_TYPES.OPEN_DIALOG,
        dialogType: dialogTypes.APPLICATION_TYPE,
        data: {
          initialApplicationTypeID: candidate.applicationTypeID,
          candidateID: candidate.id
        }
      });
      break;
    }
    case COL_NUM_MAPPING.coreEnabled:
    case COL_NUM_MAPPING.writtenSWEnabled:
    case COL_NUM_MAPPING.writtenFXEnabled:
    case COL_NUM_MAPPING.numCranesSW:
    case COL_NUM_MAPPING.numCranesFX: {
      dispatch({
        type: ACTION_TYPES.OPEN_DIALOG,
        dialogType: dialogTypes.CUSTOM_APPLICATION_FORM,
        data: {
          candidate
        }
      });
      break;
    }
    case COL_NUM_MAPPING.practicalCharges:
    case COL_NUM_MAPPING.practicalRetestFee:
    case COL_NUM_MAPPING.writtenCharges:
    case COL_NUM_MAPPING.lateFee:
    case COL_NUM_MAPPING.incompleteFee:
    case COL_NUM_MAPPING.walkInFee:
    case COL_NUM_MAPPING.otherFee:
    case COL_NUM_MAPPING.practiceTimeCharge:
    case COL_NUM_MAPPING.customerCharges:
    case COL_NUM_MAPPING.amountPaid:
    case COL_NUM_MAPPING.amountDue:
    case COL_NUM_MAPPING.paymentStatus: {
      dispatch({
        type: ACTION_TYPES.OPEN_DIALOG,
        dialogType: dialogTypes.PAYMENT,
        data: {
          candidateID: candidate.id
        }
      });
      break;
    }
    case COL_NUM_MAPPING.payeeType: {
      dispatch({
        type: ACTION_TYPES.OPEN_DIALOG,
        dialogType: dialogTypes.PAYEE_TYPE,
        data: {
          candidateID: candidate.id
        }
      });
      break;
    }
    case COL_NUM_MAPPING.gradeCore:
    case COL_NUM_MAPPING.gradeWrittenSW:
    case COL_NUM_MAPPING.gradeWrittenFX:
    case COL_NUM_MAPPING.gradePracticalSW:
    case COL_NUM_MAPPING.gradePracticalFX: {
      dispatch({
        type: ACTION_TYPES.OPEN_DIALOG,
        dialogType: dialogTypes.GRADES,
        data: {
          candidateID: candidate.id
        }
      });
      break;
    }
    case COL_NUM_MAPPING.practice: {
      dispatch({
        type: ACTION_TYPES.OPEN_DIALOG,
        dialogType: dialogTypes.PAYMENT,
        data: {
          candidateID: candidate.id
        }
      });
      break;
    }
    case COL_NUM_MAPPING.signedWFormReceived:
    case COL_NUM_MAPPING.signedPFormReceived:
    case COL_NUM_MAPPING.confirmationEmailLastSent:
    case COL_NUM_MAPPING.appFormSentToNccco: {
      dispatch({
        type: ACTION_TYPES.OPEN_DIALOG,
        dialogType: dialogTypes.CANDIDATE_CHECKLIST,
        data: {
          candidateID: candidate.id
        }
      });
      break;
    }
    default: {
      dispatch({
        type: ACTION_TYPES.FOCUS_CELL,
        row,
        col,
        table
      });
    }
  }
};

export const cancelFocusCell = () => dispatch => {
  dispatch({
    type: ACTION_TYPES.BLUR_CELL
  });
};

export const blurCell = (candidateID, value, col) => (dispatch, getState) => {
  const isValid = validate(value, col);
  const state = getState();
  let preparedApiValue;

  try {
    preparedApiValue = prepareApiValue(candidateID, value, col, state);
  } catch (err) {
    dispatch({
      dialogType: dialogTypes.ERROR,
      type: ACTION_TYPES.OPEN_DIALOG,
      data: {
        title: 'Invalid data',
        body: err.message
      }
    });
    return;
  }

  if (isValid) {
    apiUpdateCandidateField(candidateID, preparedApiValue, col)
      .then(({ data }) => {
        dispatch({
          type: ACTION_TYPES.BLUR_CELL
        });
        dispatch({
          type: ACTION_TYPES.CLOSE_DIALOG
        });
        dispatch(generateDispatchObject(candidateID, value, col, data));
      })
      .catch(() => {
        dispatch({
          dialogType: dialogTypes.ERROR,
          type: ACTION_TYPES.OPEN_DIALOG,
          data: {
            title: 'Error updating server',
            body: 'Please try again.'
          }
        });
      });
  } else {
    dispatch({
      dialogType: dialogTypes.ERROR,
      type: ACTION_TYPES.OPEN_DIALOG,
      data: errorMessages[col] || {
        title: 'Error',
        body: 'Input error, please enter a valid value.'
      }
    });
  }
};

export const setView = view => ({
  type: ACTION_TYPES.SET_VIEW,
  payload: view
});

export const setVisibleColumns = columns => dispatch => {
  dispatch({
    type: ACTION_TYPES.SET_VISIBLE_COLUMNS,
    payload: columns
  });
  dispatch(closeDialog());
};
