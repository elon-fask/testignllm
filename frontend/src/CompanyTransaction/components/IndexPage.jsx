import React from 'react';
import { withRouter } from 'react-router-dom';
import { formatMoney } from 'accounting';
import { companyTxTypesStr } from '../../common/companyTransactions';

function IndexPage(props) {
  return (
    <div>
      <table className="table table-striped">
        <thead>
          <tr>
            <th>Company</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Details</th>
            <th>Last Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {props.transactionsById.map(id => {
            const transaction = props.transactions[id];
            const company = props.companies[transaction.company_id].name;
            const type = companyTxTypesStr[transaction.type];
            const amount = formatMoney(transaction.amount);

            return (
              <tr key={transaction.id}>
                <td>{company}</td>
                <td>{type}</td>
                <td>{amount}</td>
                <td>Details</td>
                <td>{transaction.updated_at}</td>
                <td>
                  <div>
                    <button type="button" className="btn btn-danger" style={{ marginRight: '8px' }}>
                      <i className="fa fa-trash" aria-hidden="true" />
                    </button>
                    <button type="button" className="btn btn-primary" style={{ marginRight: '8px' }}>
                      <i
                        className="fa fa-info-circle"
                        aria-hidden="true"
                        onClick={() => {
                          console.log('Reached!');
                          props.history.push(`/details/${transaction.id}`);
                        }}
                      />
                    </button>
                  </div>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
}

export default withRouter(IndexPage);
