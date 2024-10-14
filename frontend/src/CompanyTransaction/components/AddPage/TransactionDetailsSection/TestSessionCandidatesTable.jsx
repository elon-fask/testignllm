import React from 'react';
import { formatMoney } from 'accounting';
import { Field } from 'formik';

function TestSessionCandidatesTable(props) {
  return (
    <table className="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Company</th>
          <th>Test Session</th>
          <th>Amount to be Paid</th>
          <th>Amount Due</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {props.candidateIds.map(id => {
          const candidate = props.selectedCandidates[id];

          return (
            <tr key={candidate.id}>
              <td>{candidate.name}</td>
              <td>{candidate.companyName}</td>
              <td>{candidate.testSession}</td>
              <td>
                <div>
                  <Field type="number" name={`selectedCandidates.${candidate.id}.amountToBePaid`} />
                </div>
              </td>
              <td>{formatMoney(candidate.amountDue)}</td>
              <td>
                <button
                  type="button"
                  className="btn btn-danger"
                  data-candidate-id={candidate.id}
                  onClick={props.handleDeleteBtnClick}
                >
                  <i className="fa fa-minus" aria-hidden="true" />
                </button>
              </td>
            </tr>
          );
        })}
      </tbody>
    </table>
  );
}

export default TestSessionCandidatesTable;
