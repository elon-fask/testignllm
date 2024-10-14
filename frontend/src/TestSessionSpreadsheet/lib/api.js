import axios from 'axios';
import { COL_NUM_MAPPING } from './refs';
import { apiUpdateCandidateTransactionBatch } from '../../common/api';

const placeholderApiMethod = () =>
  new Promise(resolve => {
    resolve(true);
  });

export const apiUpdateCandidate = (id, data) => axios.post(`/admin/candidates/update-json?id=${id}`, data);

const apiUpdateCandidateName = (id, name) => {
  const splitName = name.split(', ');
  return apiUpdateCandidate(id, {
    first_name: splitName[1],
    last_name: splitName[0]
  });
};

const apiUpdateCandidateCompany = (id, company) =>
  apiUpdateCandidate(id, {
    company_name: company
  });

const apiUpdateCandidateInstructorNotes = (id, instructorNotes) =>
  apiUpdateCandidate(id, {
    instructor_notes: instructorNotes
  });

const apiUpdateApplicationForm = (id, applicationTypeID) => {
  const postData = {
    application_type_id: applicationTypeID,
    custom_form_setup: '[]'
  };
  return apiUpdateCandidate(id, postData);
};

const apiUpdateCandidateCustomForm = (id, customFormSetupString) => {
  const postData = {
    custom_form_setup: customFormSetupString
  };
  return apiUpdateCandidate(id, postData);
};

const apiUpdateCandidateInvoiceNumber = (id, invoiceNumber) =>
  apiUpdateCandidate(id, { invoice_number: invoiceNumber });

const apiUpdateCandidatePurchaseOrderNumber = (id, purchaseOrderNumber) =>
  apiUpdateCandidate(id, { purchase_order_number: purchaseOrderNumber });

export const apiUpdateCandidateTransaction = (id, transaction) =>
  axios.post(
    `/admin/candidates/update-transaction-json?candidateId=${id}&transactionId=${transaction.id}`,
    transaction
  );

export const apiCreateCandidateTransaction = (id, transaction) =>
  axios.post(`/admin/candidates/update-transaction-json?candidateId=${id}`, transaction);

export const apiDeleteCandidateTransaction = (id, transactionId, isPending = false) => {
  const pending = isPending ? '1' : '0';

  return axios.delete(
    `/admin/candidates/update-transaction-json?candidateId=${id}&transactionId=${transactionId}&pending=${pending}`
  );
};

export const apiAddPracticalTrainingSession = data =>
  axios.post('/admin/testsession/add-practical-training-session', data);

export const apiUpdateCandidateGrade = (id, { testSessionId, grades }) =>
  axios.post(`/admin/candidates/update-grades-json?candidateId=${id}&testSessionId=${testSessionId}`, { grades });

export const apiUpdateCandidateChecklist = (id, type, isReset = false) =>
  axios.post(`/api/candidates/update-checklist?id=${id}&type=${type}`, { isReset });

export const apiBulkUpdateCandidateChecklist = (type, ids) =>
  axios.post(`/api/candidates/bulk-update-checklist?type=${type}`, { candidateIDs: ids });

export const apiUpdateCandidateField = (id, apiPayload, col) => {
  switch (col) {
    case COL_NUM_MAPPING.name: {
      return apiUpdateCandidateName(id, apiPayload);
    }
    case COL_NUM_MAPPING.company: {
      return apiUpdateCandidateCompany(id, apiPayload);
    }
    case COL_NUM_MAPPING.applicationType: {
      return apiUpdateApplicationForm(id, apiPayload);
    }
    case COL_NUM_MAPPING.coreEnabled:
    case COL_NUM_MAPPING.writtenSWEnabled:
    case COL_NUM_MAPPING.writtenFXEnabled:
    case COL_NUM_MAPPING.numCranesSW:
    case COL_NUM_MAPPING.numCranesFX: {
      return apiUpdateCandidateCustomForm(id, apiPayload);
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
      return apiUpdateCandidateTransactionBatch(id, apiPayload);
    }
    case COL_NUM_MAPPING.payeeType: {
      return apiUpdateCandidate(id, apiPayload);
    }
    case COL_NUM_MAPPING.invoiceNumber: {
      return apiUpdateCandidateInvoiceNumber(id, apiPayload);
    }
    case COL_NUM_MAPPING.purchaseOrderNumber: {
      return apiUpdateCandidatePurchaseOrderNumber(id, apiPayload);
    }
    case COL_NUM_MAPPING.gradeCore:
    case COL_NUM_MAPPING.gradeWrittenSW:
    case COL_NUM_MAPPING.gradeWrittenFX:
    case COL_NUM_MAPPING.gradePracticalSW:
    case COL_NUM_MAPPING.gradePracticalFX: {
      return apiUpdateCandidateGrade(id, apiPayload);
    }
    case COL_NUM_MAPPING.instructorNotes: {
      return apiUpdateCandidateInstructorNotes(id, apiPayload);
    }
    default: {
      return placeholderApiMethod(id, apiPayload);
    }
  }
};
