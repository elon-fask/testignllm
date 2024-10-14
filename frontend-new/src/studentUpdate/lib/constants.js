export const dialogTypes = {
  NONE: 'None',
  SIGNED_W_FORM: 'Confirm Signed Written Form Received',
  SIGNED_P_FORM: 'Confirm Signed Practical Form Received',
  CONFIRM_EMAIL: 'Send Confirmation Email',
  SENT_TO_NCCCO: 'Confirm Application Forms Sent to NCCCO',
  ADD_PRACTICAL_SCHEDULE: 'Add Practice Time/Schedule Practical Exam',
  DELETE_PRACTICAL_SCHEDULE: 'Delete Practical Test Schedule',
  SET_PRACTICE_TIME_CREDITS: 'Set Practice Time Credits',
  CHANGE_SESSION: 'Change Session'
};

export const dialogTypeApiMapping = {
  SIGNED_W_FORM: 'signed_w_form_received',
  SIGNED_P_FORM: 'signed_p_form_received',
  CONFIRM_EMAIL: 'confirmation_email_last_sent',
  SENT_TO_NCCCO: 'app_form_sent_to_nccco'
};
