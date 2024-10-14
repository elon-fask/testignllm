export type UserLogType =
  | 'LOG_IN'
  | 'LOG_OUT'
  | 'POST_PENDING_TX'
  | 'CONFIRM_START_TRAINING'
  | 'CONFIRM_STOP_TRAINING'
  | 'TRAINING_CANDIDATE'
  | 'DELETE_TRAINING_SESSION'
  | 'CONFIRM_DECLINE_TEST'
  | 'UPLOAD_CANDIDATE_PHOTO'
  | 'UPLOAD_TRAINING_PHOTO'
  | 'UPLOAD_SCORE_SHEET'
  | 'UPLOAD_TEST_SESSION_PHOTO'
  | 'REGISTER_WALK_IN'
  | 'UNCHECK_SITE_REPORT_ITEM';

export type UserLogSourceType = 'CRANE_ADMIN' | 'CRANE_TRX' | 'TRAVEL_FORM';

export type UserLog = {
  id: number;
  userId: number;
  type: UserLogType;
  location: {
    lat: number;
    long: number;
    reverseGeocode: string;
  };
  source: UserLogSourceType;
  details: {
    candidateId: number;
    candidate: {
      id: number;
      md5Hash: string;
      firstName: string;
      lastName: string;
    };
    testSessionId: number;
    testSession: {
      id: number;
      sessionNumber: string;
      startDate: string;
      testSite: {
        city: string;
        state: string;
      };
    };
    checklistStepId: string;
    geolocationErrorCode: number;
  };
  createdAt: string;
  updatedAt: string;
};

export const actionStr = {
  LOG_IN: 'Log In',
  LOG_OUT: 'Log Out',
  POST_PENDING_TX: 'Post Pending Transaction',
  CONFIRM_START_TRAINING: 'Confirm Start Training',
  CONFIRM_STOP_TRAINING: 'Confirm Stop Training',
  TRAINING_CANDIDATE: 'Training Candidate',
  DELETE_TRAINING_SESSION: 'Delete Training Session',
  CONFIRM_DECLINE_TEST: 'Confirm Decline Test',
  UPLOAD_CANDIDATE_PHOTO: 'Upload Candidate Photo',
  UPLOAD_TRAINING_PHOTO: 'Upload Training Photo',
  UPLOAD_SCORE_SHEET: 'Upload Score Sheet',
  UPLOAD_TEST_SESSION_PHOTO: 'Upload Test Session Photo',
  REGISTER_WALK_IN: 'Register Walk-in Candidate',
  CHECK_SITE_REPORT_ITEM: 'Check Site Report Item',
  UNCHECK_SITE_REPORT_ITEM: 'Uncheck Site Report Item'
};

export const sourceStr = {
  CRANE_ADMIN: 'Crane Admin',
  CRANE_TRX: 'CraneTrx',
  TRAVEL_FORM: 'Travel Form'
};
