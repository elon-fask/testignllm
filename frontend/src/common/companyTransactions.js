export const companyTxTypes = [
  'PAYMENT_CASH',
  'PAYMENT_CHECK',
  'PAYMENT_AUTHORIZE_NET',
  'PAYMENT_SQUARE',
  'PAYMENT_INTUIT',
  'PAYMENT_OTHER'
];

export const companyPaymentTxTypes = [
  'PAYMENT_CASH',
  'PAYMENT_CHECK',
  'PAYMENT_AUTHORIZE_NET',
  'PAYMENT_SQUARE',
  'PAYMENT_INTUIT',
  'PAYMENT_OTHER'
];

export const companyTxTypesStr = {
  PAYMENT_CASH: 'Payment - Cash',
  PAYMENT_CHECK: 'Payment - Check',
  PAYMENT_AUTHORIZE_NET: 'Payment - Authorize.Net',
  PAYMENT_SQUARE: 'Payment - Square',
  PAYMENT_INTUIT: 'Payment - Intuit',
  PAYMENT_OTHER: 'Payment - Other'
};

function getAdjustedAmount(amountReceived, percentageAdjustment) {
  const percentFloat = percentageAdjustment * 0.01;
  const percentMultiplier = 1 - percentFloat;
  return amountReceived / percentMultiplier;
}

export function getAmountValues(amountReceived, totalAmountDuesPaid, percentageAdjustment) {
  const maximumAmountCoverage = percentageAdjustment
    ? getAdjustedAmount(amountReceived, percentageAdjustment)
    : amountReceived;
  const usableAmount = amountReceived || 0;
  const amountLeftTypical = usableAmount - totalAmountDuesPaid;
  const shouldShowmaximumAmountCoverage = !!percentageAdjustment;
  const amountLeft = shouldShowmaximumAmountCoverage ? maximumAmountCoverage - totalAmountDuesPaid : amountLeftTypical;

  return {
    maximumAmountCoverage,
    shouldShowmaximumAmountCoverage,
    amountLeft
  };
}
