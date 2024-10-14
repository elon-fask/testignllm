import { actionTypes } from '../actionCreators';
import { getFeeTotal } from '../lib/helpers';
import { gradeValues } from '../../common/grades';

export const cellTypes = {
  READONLY: 'READONLY',
  EDITABLE: 'EDITABLE',
  DIALOG: 'DIALOG'
};

export const COL_NUM_MAPPING = {
  name: 0,
  company: 1,
  applicationType: 2,
  coreEnabled: 3,
  writtenSWEnabled: 4,
  writtenFXEnabled: 5,
  numCranesSW: 6,
  numCranesFX: 7,
  practicalCharges: 8,
  practicalRetestFee: 9,
  writtenCharges: 10,
  lateFee: 11,
  incompleteFee: 12,
  walkInFee: 13,
  otherFee: 14,
  practiceTimeCharge: 15,
  customerCharges: 16,
  amountPaid: 17,
  amountDue: 18,
  paymentStatus: 19,
  payeeType: 38,
  invoiceNumber: 20,
  purchaseOrderNumber: 21,
  gradeCore: 22,
  gradeWrittenSW: 23,
  gradeWrittenFX: 24,
  gradePracticalSW: 25,
  gradePracticalFX: 26,
  cellPhone: 27,
  notes: 28,
  practice: 29,
  signedWFormReceived: 30,
  signedPFormReceived: 31,
  confirmationEmailLastSent: 32,
  appFormSentToNccco: 33,
  practicalScheduleDay: 34,
  practicalScheduleDate: 35,
  practicalScheduleTime: 36,
  practicalScheduleNewOrRetest: 37
};

export const columnFieldMap = ['name', 'company'];

export const prepareApiValue = (candidateID, value, col, state) => {
  const defaultBuilder = () => value;

  const buildCustomFormsObject = () => {
    const candidate = state.candidates[candidateID];
    const applicationType = state.applicationTypes[candidate.applicationTypeID];

    const { checkedFees, formSetup } = value;

    const writtenPart = checkedFees.reduce(
      (acc, checkedFee) => ({
        ...acc,
        [checkedFee]: 'on'
      }),
      {}
    );

    writtenPart.W_EXAM_CORE = formSetup.coreEnabled ? 'on' : 'off';
    writtenPart.W_EXAM_TLL = formSetup.writtenSWEnabled ? 'on' : 'off';
    if (applicationType.isRecert) {
      writtenPart.W_EXAM_TLL_LINK_BELT = formSetup.writtenSWEnabled ? 'on' : 'off';
    } else {
      writtenPart['W_EXAM_TLL_LINK-BELT'] = formSetup.writtenSWEnabled ? 'on' : 'off';
    }

    writtenPart.W_EXAM_TSS = formSetup.writtenFXEnabled ? 'on' : 'off';
    writtenPart.W_EXAM_TSS_MANITEX = formSetup.writtenFXEnabled ? 'on' : 'off';
    writtenPart.W_FEE_LATE = formSetup.lateFeeEnabled ? 'on' : 'off';
    writtenPart.W_FEE_INCOMPLETE = formSetup.incompleteFeeEnabled ? 'on' : 'off';
    writtenPart.W_TOTAL_DUE = getFeeTotal(checkedFees, applicationType.isRecert);

    const practicalPart = {};
    practicalPart.P_TELESCOPIC_TLL = formSetup.practicalSWEnabled ? 'on' : 'off';
    practicalPart.P_TELESCOPIC_TSS = formSetup.practicalFXEnabled ? 'on' : 'off';

    const candidateHasRetests = candidate.transactions.reduce(
      (acc, transaction) => acc || (transaction.paymentType === 10 && transaction.chargeType === 50),
      false
    );

    const practicalPartIsUpdated =
      candidate.customFormSetup.practicalSWEnabled !== formSetup.practicalSWEnabled ||
      candidate.customFormSetup.practicalFXEnabled !== formSetup.practicalFXEnabled;

    if (candidateHasRetests && practicalPartIsUpdated) {
      throw new Error(
        'Practical tests cannot be updated since candidate has already been charged with Practical Retest fees.'
      );
    }

    const writtenApplicationFormName = applicationType.isRecert
      ? 'iai-blank-recert-with-1000-hours-application'
      : 'iai-blank-written-test-site-application-new-candidate';

    const customFormApiObj = {
      [writtenApplicationFormName]: writtenPart,
      'iai-blank-practical-test-application-form': practicalPart
    };

    return JSON.stringify(customFormApiObj);
  };

  const buildGradesApiPayload = () => ({
    testSessionId: state.testSession.id,
    grades: value
  });

  switch (col) {
    case COL_NUM_MAPPING.coreEnabled:
    case COL_NUM_MAPPING.writtenSWEnabled:
    case COL_NUM_MAPPING.writtenFXEnabled:
    case COL_NUM_MAPPING.numCranesSW:
    case COL_NUM_MAPPING.numCranesFX: {
      return buildCustomFormsObject();
    }
    case COL_NUM_MAPPING.gradeCore:
    case COL_NUM_MAPPING.gradeWrittenSW:
    case COL_NUM_MAPPING.gradeWrittenFX:
    case COL_NUM_MAPPING.gradePracticalSW:
    case COL_NUM_MAPPING.gradePracticalFX: {
      return buildGradesApiPayload();
    }
    case COL_NUM_MAPPING.payeeType: {
      return {
        is_company_sponsored: value === '1'
      };
    }
    default: {
      return defaultBuilder();
    }
  }
};

const generatePayload = (value, col, apiData) => {
  const buildGradesObject = () =>
    Object.keys(value).reduce((acc, key) => {
      return {
        ...acc,
        [key]: gradeValues[value[key]]
      };
    }, {});

  switch (col) {
    case COL_NUM_MAPPING.name: {
      return {
        name: value
      };
    }
    case COL_NUM_MAPPING.company: {
      return {
        company: value
      };
    }
    case COL_NUM_MAPPING.applicationType: {
      return {
        applicationTypeID: value,
        customFormSetup: {},
        customCheckedFees: []
      };
    }
    case COL_NUM_MAPPING.coreEnabled:
    case COL_NUM_MAPPING.writtenSWEnabled:
    case COL_NUM_MAPPING.writtenFXEnabled:
    case COL_NUM_MAPPING.numCranesSW:
    case COL_NUM_MAPPING.numCranesFX: {
      return {
        customFormSetup: value.formSetup,
        customCheckedFees: value.checkedFees
      };
    }
    case COL_NUM_MAPPING.practicalCharges:
    case COL_NUM_MAPPING.practicalRetestFee:
    case COL_NUM_MAPPING.writtenCharges:
    case COL_NUM_MAPPING.lateFee:
    case COL_NUM_MAPPING.incompleteFee:
    case COL_NUM_MAPPING.walkInFee:
    case COL_NUM_MAPPING.otherFee:
    case COL_NUM_MAPPING.practiceTimeCharge:
    case COL_NUM_MAPPING.customerCharges:
    case COL_NUM_MAPPING.amountPaid:
    case COL_NUM_MAPPING.amountDue:
    case COL_NUM_MAPPING.paymentStatus: {
      return {
        transactions: apiData.transactions
      };
    }
    case COL_NUM_MAPPING.payeeType: {
      if (typeof apiData.is_company_sponsored === 'undefined' || apiData.is_company_sponsored === null) {
        return { isCompanySponsored: null };
      }

      return {
        isCompanySponsored: apiData.is_company_sponsored ? 1 : 0
      };
    }
    case COL_NUM_MAPPING.invoiceNumber: {
      return {
        invoiceNumber: value
      };
    }
    case COL_NUM_MAPPING.purchaseOrderNumber: {
      return {
        purchaseOrderNumber: value
      };
    }
    case COL_NUM_MAPPING.gradeCore:
    case COL_NUM_MAPPING.gradeWrittenSW:
    case COL_NUM_MAPPING.gradeWrittenFX:
    case COL_NUM_MAPPING.gradePracticalSW:
    case COL_NUM_MAPPING.gradePracticalFX: {
      return {
        grades: buildGradesObject(value)
      };
    }
    case COL_NUM_MAPPING.instructorNotes: {
      return {
        instructorNotes: value
      };
    }
    default: {
      return {};
    }
  }
};

export const generateDispatchObject = (candidateID, value, col, apiData) => {
  const defaultDispatch = {
    type: actionTypes.UPDATE_CANDIDATE,
    candidateID,
    payload: generatePayload(value, col, apiData)
  };

  switch (col) {
    default: {
      return defaultDispatch;
    }
  }
};
