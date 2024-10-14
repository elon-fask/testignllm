import Yup from 'yup';

export function getIterResultAllCandidateSelected(acc, id, selectedCandidates) {
  return selectedCandidates[id] ? acc : false;
}

export function getIterResultTotalAmountDuesPaid(acc, id, formValues) {
  if (formValues.candidateSelection[id]) {
    return acc + parseFloat(formValues[`payment-${id}`]);
  }
  return acc;
}

export function getIterResultCandidateSelectionSchema(acc, id) {
  return {
    ...acc,
    [id]: Yup.boolean()
  };
}

export function getIterResultCandidatePaymentSchema(acc, id, candidateSelection, candidates) {
  const isSelected = candidateSelection[id];

  if (isSelected) {
    const { amountDue } = candidates[id];
    return {
      ...acc,
      [`payment-${id}`]: Yup.number()
        .max(amountDue, 'Amount Paid cannot exceed Amount Due.')
        .required('Amount Paid is required')
    };
  }
  return {
    ...acc,
    [`payment-${id}`]: Yup.string()
  };
}
