import React from 'react';
import { formatMoney } from 'accounting';
import { connect } from 'formik';
import { getAmountValues } from '../../../../common/companyTransactions';

function TransactionSummary(props) {
  const { amountReceived, selectedCandidates, applyPercentageAdjustment, percentageAdjustment } = props.formik.values;

  const totalAmountDuesPaid = Object.keys(selectedCandidates).reduce((acc, id) => {
    const { amountToBePaid } = selectedCandidates[id];
    return acc + amountToBePaid;
  }, 0);

  const { maximumAmountCoverage, shouldShowmaximumAmountCoverage, amountLeft } = getAmountValues(
    amountReceived,
    totalAmountDuesPaid,
    applyPercentageAdjustment && percentageAdjustment
  );

  return (
    <div style={{ marginTop: '24px' }}>
      <h4>Company Transaction Summary</h4>
      <div style={{ display: 'flex' }}>
        <div style={{ marginRight: '8px' }}>
          <div>Total Candidate Amount Dues Paid:</div>
          {shouldShowmaximumAmountCoverage && (
            <div>{`Maximum Amount that Payment Can Cover (+${percentageAdjustment}%):`}</div>
          )}
          <div>Amount Left to be Distributed:</div>
        </div>
        <div>
          <div>{formatMoney(totalAmountDuesPaid)}</div>
          {shouldShowmaximumAmountCoverage && <div>{formatMoney(maximumAmountCoverage)}</div>}
          <div>{formatMoney(amountLeft)}</div>
        </div>
      </div>
    </div>
  );
}

export default connect(TransactionSummary);
