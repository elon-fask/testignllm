import React from 'react';
import { formatMoney } from 'accounting';

function Roster({ isHidden, filteredRoster, handleAddBtnClick }) {
  if (isHidden) {
    return null;
  }

  if (!filteredRoster || filteredRoster.length < 1) {
    return <div>No matching candidates found.</div>;
  }

  return (
    <table className="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Company</th>
          <th>Amount Due</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {filteredRoster.map(candidate => {
          return (
            <tr key={candidate.id}>
              <td>{candidate.name}</td>
              <td>{candidate.companyName}</td>
              <td>{formatMoney(candidate.amountDue)}</td>
              <td>
                <div>
                  <button
                    type="button"
                    className="btn btn-success"
                    data-candidate-id={candidate.id}
                    onClick={handleAddBtnClick}
                  >
                    <i className="fa fa-plus" aria-hidden="true" />
                  </button>
                </div>
              </td>
            </tr>
          );
        })}
      </tbody>
    </table>
  );
}

export default Roster;
