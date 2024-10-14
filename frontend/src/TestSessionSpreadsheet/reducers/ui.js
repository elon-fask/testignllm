import { actionTypes } from '../actionCreators';

export const dialogTypes = {
  ERROR: 'ERROR',
  CONFIRM: 'CONFIRM',
  APPLICATION_TYPE: 'APPLICATION_TYPE',
  CUSTOM_APPLICATION_FORM: 'CUSTOM_APPLICATION_FORM',
  PAYMENT: 'PAYMENT',
  GRADES: 'GRADES',
  BATCH_GRADE: 'BATCH_GRADE',
  CANDIDATE_CHECKLIST: 'CANDIDATE_CHECKLIST',
  COLUMN_OPTIONS: 'COLUMN_OPTIONS',
  TEST_FEES_CREDIT: 'TEST_FEES_CREDIT',
  PRACTICAL_TEST_SCHEDULE: 'PRACTICAL_TEST_SCHEDULE',
  COMPANY_PAYMENT: 'COMPANY_PAYMENT',
  PAYEE_TYPE: 'PAYEE_TYPE'
};

export const viewTypes = {
  DEFAULT: 'DEFAULT',
  PRACTICAL_TEST_SCHEDULE: 'PRACTICAL_TEST_SCHEDULE',
  GRADING: 'GRADING',
  CLASSREADINESS: 'CLASSREADINESS',
  CANDIDATE_CHECKLIST: 'CANDIDATE_CHECKLIST',
  NOGRADES: 'NOGRADES',
  BOOKKEEPING: 'BOOKKEEPING',
  BOOKKEEPING_BACKLOG: 'BOOKKEEPING_BACKLOG',
  APPFORMS: 'APPFORMS',
  ALL: 'ALL',
  CUSTOM: 'CUSTOM'
};

const dialogDefaultState = {
  isOpen: false,
  type: dialogTypes.ERROR,
  data: {
    title: 'Error',
    body: ''
  }
};

const selectedCellDefaultState = {
  row: -1,
  col: -1,
  table: 'none',
  hasError: false
};

export const views = {
  DEFAULT: [
    'applicationType',
    'coreEnabled',
    'writtenSWEnabled',
    'writtenFXEnabled',
    'numCranesSW',
    'numCranesFX',
    'practicalCharges',
    'writtenCharges',
    'lateFee',
    'incompleteFee',
    'walkInFee',
    'otherFee',
    'practiceTimeCharge',
    'customerCharges',
    'amountPaid',
    'amountDue',
    'paymentStatus',
    'payeeType',
    'invoiceNumber',
    'purchaseOrderNumber',
    'gradeCore',
    'gradeWrittenSW',
    'gradeWrittenFX',
    'gradePracticalSW',
    'gradePracticalFX',
    'signedWFormReceived',
    'signedPFormReceived',
    'confirmationEmailLastSent',
    'appFormSentToNccco'
  ],
  PRACTICAL_TEST_SCHEDULE: [
    'applicationType',
    'practicalScheduleDay',
    'practicalScheduleDate',
    'practicalScheduleTime',
    'practicalScheduleNewOrRetest',
    'numCranesSW',
    'numCranesFX',
    'practice',
    'amountDue',
    'paymentStatus',
    'cellPhone',
    'notes',
    'actions'
  ],
  GRADING: ['applicationType', 'gradeCore', 'gradeWrittenSW', 'gradeWrittenFX', 'gradePracticalSW', 'gradePracticalFX'],
  CLASSREADINESS: [
    'applicationType',
    'coreEnabled',
    'writtenSWEnabled',
    'writtenFXEnabled',
    'numCranesSW',
    'numCranesFX',
    'practice',
    'amountDue',
    'paymentStatus',
    'cellPhone',
    'notes'
  ],
  CANDIDATE_CHECKLIST: [
    'signedWFormReceived',
    'signedPFormReceived',
    'confirmationEmailLastSent',
    'appFormSentToNccco'
  ],
  NOGRADES: [
    'applicationType',
    'coreEnabled',
    'writtenSWEnabled',
    'writtenFXEnabled',
    'numCranesSW',
    'numCranesFX',
    'practicalCharges',
    'writtenCharges',
    'lateFee',
    'incompleteFee',
    'walkInFee',
    'otherFee',
    'practiceTimeCharge',
    'customerCharges',
    'amountPaid',
    'amountDue',
    'paymentStatus',
    'invoiceNumber',
    'purchaseOrderNumber'
  ],
  BOOKKEEPING: [
    'applicationType',
    'practicalCharges',
    'writtenCharges',
    'lateFee',
    'incompleteFee',
    'walkInFee',
    'otherFee',
    'practiceTimeCharge',
    'customerCharges',
    'amountPaid',
    'amountDue',
    'paymentStatus',
    'payeeType',
    'invoiceNumber',
    'purchaseOrderNumber',
    'actions'
  ],
  BOOKKEEPING_BACKLOG: [
    'amountPaid',
    'amountDue',
    'paymentStatus',
    'payeeType',
    'invoiceNumber',
    'purchaseOrderNumber',
    'actions'
  ],
  APPFORMS: ['applicationType', 'coreEnabled', 'writtenSWEnabled', 'writtenFXEnabled', 'numCranesSW', 'numCranesFX'],
  ALL: [
    'applicationType',
    'coreEnabled',
    'writtenSWEnabled',
    'writtenFXEnabled',
    'numCranesSW',
    'numCranesFX',
    'practicalCharges',
    'writtenCharges',
    'lateFee',
    'incompleteFee',
    'walkInFee',
    'otherFee',
    'practiceTimeCharge',
    'customerCharges',
    'amountPaid',
    'amountDue',
    'paymentStatus',
    'invoiceNumber',
    'purchaseOrderNumber',
    'gradeCore',
    'gradeWrittenSW',
    'gradeWrittenFX',
    'gradePracticalSW',
    'gradePracticalFX',
    'signedWFormReceived',
    'signedPFormReceived',
    'confirmationEmailLastSent',
    'appFormSentToNccco',
    'cellPhone',
    'notes',
    'practice'
  ]
};

const defaultViewOptions = {
  showTestSessionTitle: true,
  showTestSessionInfo: false,
  combineStudents: false,
  countPracticalCranes: true,
  showTotalsTable: true,
  showSummaryTable: true,
  rowHeight: null
};

export const viewOptionsKeys = Object.keys(defaultViewOptions);

export const viewOptions = {
  DEFAULT: defaultViewOptions,
  PRACTICAL_TEST_SCHEDULE: {
    ...defaultViewOptions,
    combineStudents: true,
    countPracticalCranes: false,
    showTotalsTable: false,
    showSummaryTable: false,
    rowHeight: '60px'
  },
  GRADING: {
    ...defaultViewOptions,
    showTotalsTable: false,
    showSummaryTable: false
  },
  CLASSREADINESS: {
    ...defaultViewOptions,
    showTestSessionTitle: false,
    showTestSessionInfo: true,
    countPracticalCranes: false,
    showTotalsTable: false,
    showSummaryTable: false
  },
  CANDIDATE_CHECKLIST: {
    ...defaultViewOptions
  },
  NOGRADES: {
    ...defaultViewOptions
  },
  BOOKKEEPING: {
    ...defaultViewOptions,
    combineStudents: true,
    showSummaryTable: false,
    rowHeight: '60px'
  },
  BOOKKEEPING_BACKLOG: {
    ...defaultViewOptions,
    combineStudents: true,
    showSummaryTable: false,
    rowHeight: '60px'
  },
  APPFORMS: {
    ...defaultViewOptions
  },
  CUSTOM: {
    ...defaultViewOptions
  },
  ALL: defaultViewOptions
};

export const uiDefaultState = {
  dialog: dialogDefaultState,
  selectedCell: selectedCellDefaultState,
  columns: views.DEFAULT,
  view: viewTypes.DEFAULT,
  showTestSessionInfo: false,
  options: viewOptions[viewTypes.DEFAULT],
  printerFriendly: false
};

const DialogReducer = (state = dialogDefaultState, action) => {
  switch (action.type) {
    case actionTypes.OPEN_DIALOG: {
      return {
        isOpen: true,
        type: action.dialogType,
        data: action.data
      };
    }
    case actionTypes.CLOSE_DIALOG: {
      return dialogDefaultState;
    }
    default: {
      return state;
    }
  }
};

const SelectedCellReducer = (state = selectedCellDefaultState, action) => {
  switch (action.type) {
    case actionTypes.FOCUS_CELL: {
      return {
        row: action.row,
        col: action.col,
        table: action.table
      };
    }
    case actionTypes.BLUR_CELL: {
      return selectedCellDefaultState;
    }
    default: {
      return state;
    }
  }
};

export const UIReducer = (state = uiDefaultState, action) => {
  switch (action.type) {
    case actionTypes.OPEN_DIALOG:
    case actionTypes.CLOSE_DIALOG: {
      return {
        ...state,
        dialog: DialogReducer(state.dialog, action)
      };
    }
    case actionTypes.FOCUS_CELL:
    case actionTypes.BLUR_CELL: {
      return {
        ...state,
        selectedCell: SelectedCellReducer(state.cell, action)
      };
    }
    case actionTypes.SET_VIEW: {
      return {
        ...state,
        view: action.payload,
        columns: views[action.payload],
        options: viewOptions[action.payload]
      };
    }
    case actionTypes.SET_VISIBLE_COLUMNS: {
      return {
        ...state,
        columns: action.payload
      };
    }
    default: {
      return state;
    }
  }
};
