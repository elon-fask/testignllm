import axios from 'axios';

export const apiFindTestSession = (startDate, endDate) => {
  return axios.get(`/api/test-session/find?startDate=${startDate}&endDate=${endDate}`);
};

export const apiFindCompanies = testSessionIds => {
  const queryString = testSessionIds.reduce((acc, id, index) => {
    if (index > 0) {
      return `${acc}&ids[]=${id}`;
    }
    return `ids[]=${id}`;
  }, '');

  return axios.get(`/api/candidates/find-companies?${queryString}`);
};

export const apiTransferCandidate = (
  isRescheduleOnly,
  transferWrittenAndPractical,
  candidateId,
  testSessionId,
  remarks,
  options
) => {
  const data = { isRescheduleOnly, transferWrittenAndPractical };

  if (remarks) {
    data.remarks = remarks;
  }

  if (typeof options !== 'undefined' && Object.keys(options).length > 0) {
    if (options.incomingApplicationTypeId) {
      data.incomingApplicationTypeId = options.incomingApplicationTypeId;
    }
    if (options.ncccoFeesOverrideCurrent) {
      data.ncccoFeesOverrideCurrent = options.ncccoFeesOverrideCurrent;
    }
    if (options.ncccoFeesOverrideIncoming) {
      data.ncccoFeesOverrideIncoming = options.ncccoFeesOverrideIncoming;
    }
    if (options.currentTransactionsDiff) {
      data.currentTransactionsDiff = options.currentTransactionsDiff;
    }
    if (options.incomingTransactions) {
      data.incomingTransactions = options.incomingTransactions;
    }
  }

  return axios.post(`/admin/candidates/select?id=${candidateId}&i=${testSessionId}`, data, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  });
};

export const apiUpdateCandidateTransactionBatch = (candidateId, data) => {
  return axios.post(`/admin/candidates/update-transaction-batch-json?candidateId=${candidateId}`, data);
};

export const apiAddTransaction = (candidateId, data, isPending = false) => {
  const pending = isPending ? '1' : '0';

  return axios.post(`/admin/candidates/update-transaction-json?candidateId=${candidateId}&pending=${pending}`, data);
};

export const apiUpdateTransaction = (candidateId, transactionId, data, isPending = false) => {
  const pending = isPending ? '1' : '0';

  return axios.post(
    `/admin/candidates/update-transaction-json?candidateId=${candidateId}&transactionId=${transactionId}&pending=${pending}`,
    data
  );
};

export const apiDeleteTransaction = (candidateId, transactionId, isPending = false) => {
  const pending = isPending ? '1' : '0';

  return axios.delete(
    `/admin/candidates/update-transaction-json?candidateId=${candidateId}&transactionId=${transactionId}&pending=${pending}`
  );
};

export const apiSetNcccoTestFeesCredit = (testSessionId, amount) => {
  return axios.post(`/api/test-session/set-nccco-test-fees-credit?id=${testSessionId}`, { amount });
};

export const apiUpdatePracticalTestSchedule = (testSessionId, payload) => {
  return axios.post(`/api/test-session/update-practical-test-schedule?id=${testSessionId}`, payload);
};

export const apiAddTestSessionDays = (testSessionId, payload) => {
  return axios.post(`/api/test-session/add-test-session-days?id=${testSessionId}`, payload);
};

export const apiDeletePracticalTestSchedule = id => {
  return axios.post(`/api/test-session/delete-practical-test-schedule?id=${id}`);
};

export const apiResetGrades = (candidateId, testSessionId) => {
  return axios.post(`/api/candidates/reset-grades?candidateId=${candidateId}&testSessionId=${testSessionId}`);
};
