import { combineReducers } from 'redux';

const ApplicationTypesReducer = (state = {}) => state;
const ApplicationTypeIdsReducer = (state = {}) => state;
const PromoCodesReducer = (state = {}) => state;
const PromoCodeIdsReducer = (state = {}) => state;
const TestSitesReducer = (state = {}) => state;
const TestSiteIdsReducer = (state = {}) => state;
const TestSessionsReducer = (state = {}) => state;
const TestSessionIdsReducer = (state = {}) => state;

const uiErrorsDefaultState = {
  selectedFileName: '',
  selectedApplicationTypeId: '',
  selectedPromoCodeId: '',
  poNumber: '',
  company: '',
  selectedTestSiteId: '',
  selectedTestSessionId: '',
  startingRowValue: '',
  endingRowValue: '',
  emailStudentsChecked: ''
};

const StudentsReducer = (state = [], action) => {
  switch (action.type) {
    case 'UPDATE_STUDENT_LIST':
      return action.students;
    default: {
      return state;
    }
  }
};

const UIErrorReducer = (state = uiErrorsDefaultState, action) => {
  switch (action.type) {
    case 'UPDATE_ERROR_VALUE':
      return {
        ...state,
        [action.fieldName]: action.value
      };
    default: {
      return state;
    }
  }
};

const uiFormValuesDefaultState = {
  selectedFileName: 'No file chosen',
  selectedApplicationTypeId: null,
  selectedPromoCodeId: null,
  poNumber: null,
  company: null,
  selectedTestSiteId: null,
  selectedTestSessionId: null,
  startingRowValue: '',
  endingRowValue: '',
  emailStudentsChecked: false,
  customizeColumnMappings: false,
  columnLastName: 'A',
  columnFirstName: 'B',
  columnEmail: 'C',
  columnPhone: 'D',
  columnBirthday: 'E',
  columnAddress: 'F',
  columnCity: 'G',
  columnState: 'H',
  columnZip: 'I',
  columnCompany: ''
};

const UIFormValuesReducer = (state = uiFormValuesDefaultState, action) => {
  switch (action.type) {
    case 'UPDATE_FORM_VALUE': {
      const newState = {
        ...state,
        [action.fieldName]: action.value
      };
      if (action.fieldName === 'selectedTestSiteId') {
        newState.selectedTestSessionId = null;
      }
      return newState;
    }
    default:
      return state;
  }
};

const uiTableDefaultState = {
  isLoadingTablePreview: false,
  isUploadingDialogVisible: false,
  isUploadingFinished: true,
  tablePreview: null,
  zipUrl: null
};

const UITableReducer = (state = uiTableDefaultState, action) => {
  switch (action.type) {
    case 'START_LOADING_TABLE_PREVIEW': {
      return { ...state, isLoadingTablePreview: true };
    }
    case 'SET_TABLE_PREVIEW_DATA': {
      return {
        ...state,
        highestColumn: action.highestColumn,
        tablePreview: action.tablePreview,
        isLoadingTablePreview: false
      };
    }
    case 'OPEN_UPLOAD_WAIT_PROMPT': {
      return { ...state, isUploadingDialogVisible: true };
    }
    case 'CLOSE_UPLOAD_WAIT_PROMPT': {
      return { ...state, isUploadingDialogVisible: false };
    }
    case 'START_UPLOADING_STUDENT_INFO': {
      return { ...state, isUploadingFinished: false };
    }
    case 'STOP_UPLOADING_STUDENT_INFO': {
      return { ...state, isUploadingFinished: true, zipUrl: action.zipUrl };
    }
    default: {
      return state;
    }
  }
};

const UITableErrorReducer = (state = [], action) => {
  switch (action.type) {
    case 'SET_TABLE_PREVIEW_ERRORS': {
      return action.errors;
    }
    default: {
      return state;
    }
  }
};

const uiModalDefaultState = {
  isVisible: false,
  type: 'TABLE_ERRORS'
};

const UIModalReducer = (state = uiModalDefaultState, action) => {
  switch (action.type) {
    case 'SHOW_TABLE_PREVIEW_ERRORS_MODAL': {
      return {
        isVisible: true,
        type: 'TABLE_ERRORS'
      };
    }
    case 'CLOSE_MODAL': {
      return {
        ...state,
        isVisible: false
      };
    }
    default: {
      return state;
    }
  }
};

const BulkRegistrationReducer = combineReducers({
  applicationTypes: ApplicationTypesReducer,
  applicationTypeIds: ApplicationTypeIdsReducer,
  promoCodes: PromoCodesReducer,
  promoCodeIds: PromoCodeIdsReducer,
  testSites: TestSitesReducer,
  testSiteIds: TestSiteIdsReducer,
  testSessions: TestSessionsReducer,
  testSessionIds: TestSessionIdsReducer,
  students: StudentsReducer,
  ui: combineReducers({
    errors: UIErrorReducer,
    formValues: UIFormValuesReducer,
    table: UITableReducer,
    tableErrors: UITableErrorReducer,
    modal: UIModalReducer
  })
});

export default BulkRegistrationReducer;
