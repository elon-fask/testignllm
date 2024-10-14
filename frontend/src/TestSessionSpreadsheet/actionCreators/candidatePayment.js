import { apiCreateCandidateTransaction, apiDeleteCandidateTransaction } from '../lib/api';
import { apiUpdateTransaction } from '../../common/api';
import { summarizeTransactions } from '../../common/candidateTransactions';
import ACTION_TYPES from './actionTypes';

export const createTransaction = (candidateId, transaction) => dispatch => {
  return new Promise((resolve, reject) => {
    apiCreateCandidateTransaction(candidateId, transaction)
      .then(({ data }) => {
        dispatch({
          type: ACTION_TYPES.CREATE_TRANSACTION,
          candidateID: candidateId,
          payload: {
            ...data,
            amount: parseFloat(data.amount)
          }
        });
        resolve();
      })
      .catch(err => {
        reject(err);
      });
  });
};

export const updateTransactionRemarks = (candidateId, transactionId, remarks) => dispatch => {
  return new Promise((resolve, reject) => {
    apiUpdateTransaction(candidateId, transactionId, { remarks }, false)
      .then(({ data }) => {
        dispatch({
          type: ACTION_TYPES.UPDATE_TRANSACTION,
          candidateID: candidateId,
          payload: data,
          isPending: false
        });
        resolve();
      })
      .catch(e => {
        reject(e);
      });
  });
};

export const deleteTransaction = (candidateId, transactionId, isPending = false) => dispatch => {
  return new Promise((resolve, reject) => {
    apiDeleteCandidateTransaction(candidateId, transactionId, isPending)
      .then(() => {
        dispatch({
          type: ACTION_TYPES.DELETE_TRANSACTION,
          candidateID: candidateId,
          payload: transactionId,
          isPending
        });
        resolve();
      })
      .catch(err => {
        reject(err);
      });
  });
};

export const approvePendingTransaction = (candidateId, pTransactionId, remarks) => (dispatch, getState) => {
  return new Promise((resolve, reject) => {
    const { candidates } = getState();
    const candidate = candidates[candidateId];
    const pendingTransaction = candidate.pendingTransactions.find(tx => tx.id === parseInt(pTransactionId, 10));

    if (!pendingTransaction) {
      reject(new Error('Pending transaction not found.'));
    }

    const payload = {
      amount: pendingTransaction.amount,
      paymentType: pendingTransaction.type > 40 ? 10 : pendingTransaction.type,
      chargeType: pendingTransaction.type > 40 ? pendingTransaction.type : undefined,
      remarks: remarks || undefined
    };

    apiCreateCandidateTransaction(candidateId, payload)
      .then(({ data }) =>
        dispatch({
          type: ACTION_TYPES.CREATE_TRANSACTION,
          candidateID: candidateId,
          payload: {
            ...data,
            amount: parseFloat(data.amount)
          }
        })
      )
      .then(apiDeleteCandidateTransaction(candidateId, pendingTransaction.id, true))
      .then(() => {
        dispatch({
          type: ACTION_TYPES.DELETE_TRANSACTION,
          candidateID: candidateId,
          payload: pendingTransaction.id,
          isPending: true
        });
        resolve();
      })
      .catch(e => {
        reject(e);
      });
  });
};

export const autoAdjustAccountBalance = candidateId => (dispatch, getState) => {
  return new Promise((resolve, reject) => {
    const { candidates } = getState();
    const candidate = candidates[candidateId];

    if (!candidate) {
      reject(new Error('Candidate not found.'));
    }

    const { amountDue } = summarizeTransactions(candidate.transactions, false);

    if (amountDue !== 0) {
      dispatch(
        createTransaction(candidateId, {
          paymentType: 31,
          amount: amountDue,
          remarks: 'Auto-adjustment to make account balance $0'
        })
      ).then(resolve);
    } else {
      resolve();
    }
  });
};
