export const transactionTypes = {
  1: 'Payment - Cash',
  2: 'Payment - Check',
  3: 'Discount - Promo',
  4: 'Payment - Electronic (Authorize.net)',
  5: 'Payment - Intuit (Swiper)',
  6: 'Payment - Other',
  7: 'Payment - Square',
  10: 'Charge - Application',
  20: 'Refund',
  30: 'Discount',
  31: 'Charge Adjustment',
  40: 'Transfer',
  50: 'Charge - NCCCO Practical Retest Fee',
  60: 'Charge - NCCCO Other Fee',
  70: 'Charge - Application',
  71: 'Charge - Walk-in Fee',
  72: 'Charge - Incomplete Application/Change Fee',
  73: 'Charge - Additional Practice Time',
  74: 'Charge - Late Fee'
};

export const paymentTypes = {
  1: 'Payment - Cash',
  2: 'Payment - Check',
  4: 'Payment - Electronic (Authorize.net)',
  5: 'Payment - Intuit (Swiper)',
  6: 'Payment - Other',
  7: 'Payment - Square'
};

export const chargeTypes = {
  50: 'NCCCO Practical Retest Fee',
  60: 'NCCCO Other Fee',
  70: 'Application/Other Charge',
  71: 'Walk-in Fee',
  72: 'Incomplete Application/Change Fee',
  73: 'Additional Practice Time',
  74: 'Late Fee'
};

export const craneTypes = {
  both: 'Both',
  sw: 'SW Cab',
  fx: 'FX Cab'
};

export function countTotalTransactions(transactions, paymentChargeMapping) {
  const result = transactions.reduce(
    (acc, transaction) =>
      paymentChargeMapping.map(({ paymentType, chargeType }, index) => {
        if (transaction.paymentType === paymentType && (chargeType ? transaction.chargeType === chargeType : true)) {
          return transaction.amount + acc[index];
        }
        return acc[index];
      }),
    paymentChargeMapping.map(() => 0)
  );
  return result;
}

export function summarizeTransactions(transactions, usesTypeIds = true) {
  const [
    charges,
    paymentCash,
    paymentCheck,
    paymentPromo,
    paymentElectronic,
    paymentIntuit,
    paymentOther,
    paymentSquare,
    refunds,
    discounts,
    chargeAdjustments
  ] = countTotalTransactions(
    usesTypeIds
      ? transactions.map(t => {
          const chargeTypes = [50, 60, 70, 71, 72, 73, 74];

          if (chargeTypes.includes(t.typeId)) {
            return {
              ...t,
              paymentType: 10
            };
          }
          return {
            ...t,
            paymentType: t.typeId
          };
        })
      : transactions,
    [
      { paymentType: 10 },
      { paymentType: 1 },
      { paymentType: 2 },
      { paymentType: 3 },
      { paymentType: 4 },
      { paymentType: 5 },
      { paymentType: 6 },
      { paymentType: 7 },
      { paymentType: 20 },
      { paymentType: 30 },
      { paymentType: 31 }
    ]
  );

  const totalDiscounts = discounts + paymentPromo;
  const grossCustomerCharges = charges - discounts - chargeAdjustments;
  const customerCharges = grossCustomerCharges - refunds;
  const amountPaid =
    paymentCash + paymentCheck + paymentPromo + paymentElectronic + paymentIntuit + paymentOther + paymentSquare;
  const amountDue = grossCustomerCharges - amountPaid;

  return {
    customerCharges,
    amountPaid,
    amountDue,
    totalCharges: charges,
    totalDiscounts,
    totalRefunds: refunds,
    totalAdjustments: chargeAdjustments
  };
}
