import _sortBy from 'lodash/sortBy';
import moment from 'moment';

export const sortPracticalTestSchedule = practicalTestSchedule =>
  _sortBy(practicalTestSchedule, [schedule => schedule.day, schedule => moment(schedule.time, 'h:mm A').unix()]);

export const parsePracticeHours = practiceHoursNum => {
  const practiceHours = parseFloat(practiceHoursNum) || null;

  if (practiceHours === null || practiceHours === 0) {
    return '';
  }

  if (practiceHours === 1) {
    return '1-Hour';
  }

  const practiceTimeStr = practiceHours.toString();
  return `${practiceTimeStr}-Hours`;
};
