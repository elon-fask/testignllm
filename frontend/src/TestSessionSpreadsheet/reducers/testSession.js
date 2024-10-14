import { actionTypes } from '../actionCreators';

/* eslint-disable import/prefer-default-export */
export const TestSessionReducer = (state = {}, action) => {
  switch (action.type) {
    case actionTypes.UPDATE_PRACTICAL_TEST_SCHEDULE: {
      let newPracticalTestSchedule = state.practicalTestSchedule;

      try {
        const { day, crane, time } = action.payload;

        const foundSchedule = state.practicalTestSchedule.find(
          schedule => schedule.day === day && schedule.time === time && schedule.crane === crane
        );

        if (foundSchedule) {
          newPracticalTestSchedule = state.practicalTestSchedule.map(schedule => {
            if (schedule.day === day && schedule.time === time && schedule.crane === crane) {
              return action.payload;
            }
            return schedule;
          });
        } else {
          newPracticalTestSchedule = [
            ...state.practicalTestSchedule,
            {
              ...action.payload,
              candidate_id: parseInt(action.payload.candidate_id, 10),
              day: parseInt(action.payload.day, 10)
            }
          ];
        }
      } catch (e) {}

      return {
        ...state,
        practicalTestSchedule: newPracticalTestSchedule
      };
    }
    case actionTypes.DELETE_PRACTICAL_TEST_SCHEDULE: {
      return {
        ...state,
        practicalTestSchedule: state.practicalTestSchedule.filter(schedule => schedule.id !== action.payload)
      };
    }
    case actionTypes.SET_NCCCO_TEST_FEES_CREDIT: {
      return {
        ...state,
        ncccoTestFeesCredit: action.payload
      };
    }
    default: {
      return state;
    }
  }
};
/* eslint-enable import/prefer-default-export */
