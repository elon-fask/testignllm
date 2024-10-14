import React, { Fragment } from 'react';
import { formatMoney } from 'accounting';

function CandidateTransactions({ candidateTransactions }) {
  if (candidateTransactions.length < 1) {
    return (
      <Fragment>
        <h4 style={{ fontWeight: 'bold' }}>Candidate Transactions</h4>
        <div>No Candidate Transactions found.</div>
      </Fragment>
    );
  }

  return (
    <Fragment>
      <h4 style={{ fontWeight: 'bold' }}>Candidate Transactions</h4>
      <div>
        <table className="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Amount</th>
              <th />
            </tr>
          </thead>
          <tbody>
            {candidateTransactions.map(tx => {
              return (
                <tr key={tx.id}>
                  <td>{tx.candidate.name}</td>
                  <td>{formatMoney(tx.amount)}</td>
                  <td>
                    <div>
                      <a
                        href={`/admin/candidates/account-balance?id=${tx.candidate.hash}`}
                        target="_blank"
                        className="btn btn-primary"
                      >
                        Account Balance
                      </a>
                    </div>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>
    </Fragment>
  );
}

export default CandidateTransactions;
