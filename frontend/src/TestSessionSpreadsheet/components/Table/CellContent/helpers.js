import { gradeCellStyles, paymentStatusStyles } from './styles';

export const getNewOrRetest = testSchedule => {
  if (testSchedule.new_or_retest === 'NEW') {
    return 'New';
  }

  if (testSchedule.new_or_retest === 'RETEST') {
    return 'Retest';
  }

  if (testSchedule.new_or_retest === 'NONE') {
    return 'None';
  }

  return '';
};

export const getGradeCellStyle = grade => gradeCellStyles[grade];
export const getPaymentStatusStyles = paymentStatus => paymentStatusStyles[paymentStatus];

export const getPracticeTimeValue = candidate => {
  if (candidate.testSchedule) {
    return candidate.testSchedule.practiceTime;
  }

  return candidate.practiceHours || '';
};

export const getPayeeType = isCompanySponsored => {
  if (isCompanySponsored === 1) {
    return 'Company';
  }

  if (isCompanySponsored === 0) {
    return 'Individual';
  }

  return '';
};
