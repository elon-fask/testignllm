import moment from 'moment';

export const SESSION_TYPE_WRITTEN = 2;
export const SESSION_TYPE_PRACTICAL = 1;

export const getClassDates = (startDateStr, endDateStr) => {
  const startDate = moment(startDateStr);
  const endDate = moment(endDateStr).subtract(1, 'd');

  if (moment.isMoment(startDate) && moment.isMoment(endDate)) {
    const inSameMonth = startDate.month() === endDate.month();

    const certDate = inSameMonth
      ? `${startDate.format('MMMM D')} - ${endDate.format('D, YYYY')}`
      : `${startDate.format('MMMM D')} - ${endDate.format('MMMM D, YYYY')}`;

    return certDate;
  }

  return '';
};
