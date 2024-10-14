import { actionTypes } from '../actionCreators';

export const candidateIDsDefaultState = [];
export const candidatesDefaultState = {};
export const candidateDefaultState = {};

const CandidateReducer = (state = candidateDefaultState, action) => {
  switch (action.type) {
    case actionTypes.UPDATE_CANDIDATE_CUSTOM_FORM: {
      return {
        ...state,
        customFormSetup: action.payload.customFormSetup,
        customCheckedFees: action.payload.customCheckedFees
      };
    }
    case actionTypes.UPDATE_CANDIDATE: {
      return {
        ...state,
        ...action.payload
      };
    }
    case actionTypes.CREATE_TRANSACTION: {
      return {
        ...state,
        transactions: [...state.transactions, action.payload]
      };
    }
    case actionTypes.UPDATE_TRANSACTION: {
      const updatedTransactions = state.transactions.map(tx => {
        if (tx.id === parseInt(action.payload.id, 10)) {
          return action.payload;
        }
        return tx;
      });

      return {
        ...state,
        transactions: updatedTransactions
      };
    }
    case actionTypes.DELETE_TRANSACTION: {
      if (action.isPending) {
        return {
          ...state,
          pendingTransactions: state.pendingTransactions.filter(t => t.id !== action.payload)
        };
      }

      return {
        ...state,
        transactions: state.transactions.filter(transaction => transaction.id !== action.payload)
      };
    }
    case actionTypes.UPDATE_PRACTICAL_TEST_SCHEDULE: {
      const payload = {};

      if (action.payload.practice_hours && state.practiceTimeCredits !== null) {
        payload.practiceTimeCredits = state.practiceTimeCredits - parseFloat(action.payload.practice_hours);
      }

      return {
        ...state,
        ...payload
      };
    }
    case actionTypes.DELETE_PRACTICAL_TEST_SCHEDULE: {
      const payload = {};

      if (action.payload.practice_hours && state.practiceTimeCredits !== null) {
        payload.practiceTimeCredits = state.practiceTimeCredits + parseFloat(action.payload.practice_hours);
      }

      return {
        ...state,
        ...payload
      };
    }
    default: {
      return state;
    }
  }
};

export const CandidatesReducer = (state = candidatesDefaultState, action) => {
  switch (action.type) {
    case actionTypes.CREATE_TRANSACTION:
    case actionTypes.UPDATE_TRANSACTION:
    case actionTypes.DELETE_TRANSACTION:
    case actionTypes.UPDATE_CANDIDATE_CUSTOM_FORM:
    case actionTypes.UPDATE_CANDIDATE: {
      return {
        ...state,
        [action.candidateID]: CandidateReducer(state[action.candidateID], action)
      };
    }
    case actionTypes.UPDATE_PRACTICAL_TEST_SCHEDULE:
    case actionTypes.DELETE_PRACTICAL_TEST_SCHEDULE: {
      return {
        ...state,
        [action.payload.candidate_id]: CandidateReducer(state[action.payload.candidate_id], action)
      };
    }
    default: {
      return state;
    }
  }
};
