import { actionTypes } from '../actionCreators';
import { UIReducer, uiDefaultState } from './ui';
import { applicationTypesDefaultState, applicationTypeIDsDefaultState } from './applicationForms';
import { CandidatesReducer, candidateIDsDefaultState, candidatesDefaultState } from './candidates';
import { TestSessionReducer } from './testSession';

const defaultState = {
  applicationTypes: applicationTypesDefaultState,
  applicationTypeIDs: applicationTypeIDsDefaultState,
  candidateIDs: candidateIDsDefaultState,
  candidates: candidatesDefaultState,
  ui: uiDefaultState
};

export default (state = defaultState, action) => {
  switch (action.type) {
    case actionTypes.CREATE_TRANSACTION:
    case actionTypes.UPDATE_TRANSACTION:
    case actionTypes.DELETE_TRANSACTION:
    case actionTypes.UPDATE_CANDIDATE_CUSTOM_FORM:
    case actionTypes.UPDATE_CANDIDATE: {
      return {
        ...state,
        candidates: CandidatesReducer(state.candidates, action)
      };
    }
    case actionTypes.OPEN_DIALOG:
    case actionTypes.CLOSE_DIALOG:
    case actionTypes.SET_VIEW:
    case actionTypes.SET_VISIBLE_COLUMNS:
    case actionTypes.FOCUS_CELL:
    case actionTypes.BLUR_CELL: {
      return {
        ...state,
        ui: UIReducer(state.ui, action)
      };
    }
    case actionTypes.SET_NCCCO_TEST_FEES_CREDIT: {
      return {
        ...state,
        testSession: TestSessionReducer(state.testSession, action)
      };
    }
    case actionTypes.UPDATE_PRACTICAL_TEST_SCHEDULE:
    case actionTypes.DELETE_PRACTICAL_TEST_SCHEDULE: {
      return {
        ...state,
        candidates: CandidatesReducer(state.candidates, action),
        testSession: TestSessionReducer(state.testSession, action)
      };
    }
    default: {
      return state;
    }
  }
};
