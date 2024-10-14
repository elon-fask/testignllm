import axios from 'axios';
import React, { Component } from 'react';
import moment from 'moment';
import ApolloClient from 'apollo-boost';
import gql from 'graphql-tag';
import { formatMoney } from 'accounting';
import Spinner from '../../../common/components/bootstrap/Spinner';
import QuickBooksLogo from '../../../common/components/third-party/QuickBooksLogo';

/* eslint-disable no-undef */
const client = new ApolloClient({
  uri: apiUrl
});
/* eslint-enable no-undef */

const query = gql`
  {
    companies {
      id
      qboId
      name
    }
  }
`;

class ImportPage extends Component {
  state = {
    preview: [],
    companies: {},
    isLoading: false
  };

  setStateAsync = payload =>
    new Promise(resolve => {
      this.setState(payload, () => {
        resolve();
      });
    });

  importQbo = async () => {
    try {
      await this.setStateAsync({ isLoading: true });
      const [qboResp, companyDataResp] = await Promise.all([
        axios.get('/admin/company/qbo-payments'),
        client.query({ query })
      ]);

      const { data: qboPaymentData } = qboResp;
      const { data: { companies: companyData } } = companyDataResp;

      this.setState({
        preview: qboPaymentData,
        isLoading: false,
        companies: companyData.reduce((acc, company) => {
          if (company.qboId) {
            return {
              ...acc,
              [company.qboId]: company
            };
          }

          return acc;
        }, {})
      });
    } catch (e) {
      this.setState({ isLoading: false });
      console.error(e);
    }
  };

  render() {
    return (
      <div>
        <button
          type="button"
          className="btn btn-primary"
          onClick={this.importQbo}
          style={{ display: 'flex', flexDirection: 'column', justifyContent: 'center' }}
        >
          <QuickBooksLogo />
          Import from QuickBooks Online (QBO)
        </button>
        {this.state.isLoading ? (
          <Spinner />
        ) : (
          <div>
            <h4>Preview:</h4>
            <div>
              {this.state.preview.length > 0 ? (
                <div>
                  <table className="table table-striped">
                    <thead>
                      <tr>
                        <th>QBO Transaction ID</th>
                        <th>Total Amount</th>
                        <th>QBO Company ID</th>
                        <th>Crane Admin ID</th>
                        <th>Company Name</th>
                        <th>Created Date</th>
                        <th>Last Updated Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      {this.state.preview.map(tx => (
                        <tr key={tx.id}>
                          <td>{tx.id}</td>
                          <td>{formatMoney(tx.totalAmt)}</td>
                          <td>{tx.qboCustomerId}</td>
                          <td>
                            {this.state.companies[tx.qboCustomerId]
                              ? this.state.companies[tx.qboCustomerId].id
                              : 'Not found'}
                          </td>
                          <td>{tx.qboCustomer.name}</td>
                          <td>{moment(tx.createdAt).format('MMM D, YYYY H:mmA')}</td>
                          <td>{moment(tx.updatedAt).format('MMM D, YYYY H:mmA')}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                'No preview data available. Please click on an import button to begin.'
              )}
            </div>
          </div>
        )}
      </div>
    );
  }
}

export default ImportPage;
