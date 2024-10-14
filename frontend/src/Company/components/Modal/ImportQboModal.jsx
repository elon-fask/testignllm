import axios from 'axios';
import React, { Component } from 'react';
import Spinner from '../../../common/components/bootstrap/Spinner';
import ModalTemplate from './ModalTemplate';

class ImportQboModal extends Component {
  state = {
    isLoading: true,
    hasError: false,
    qboResults: [],
    isWaitingForImportToFinish: false
  };

  componentDidMount = async () => {
    try {
      const { data: qboResults } = await axios.get(`/admin/company/qbo-import`);
      this.setState({
        isLoading: false,
        hasError: false,
        qboResults
      });
    } catch (e) {
      this.setState({
        isLoading: false,
        hasError: true,
        qboResults: []
      });
      console.error(e);
    }
  };

  get contents() {
    if (this.state.isLoading) {
      return <Spinner />;
    }

    if (this.state.hasError) {
      return <div>Error: could not retrieve Customer data from QuickBooks online.</div>;
    }

    return (
      <div>
        <table className="table table-striped">
          <thead>
            <tr>
              <th>QBO ID</th>
              <th>Company Name</th>
              <th>Email</th>
              <th>Phone</th>
            </tr>
          </thead>
          <tbody>
            {this.state.qboResults.map(company => (
              <tr key={company.id}>
                <td>{company.id}</td>
                <td>{company.name}</td>
                <td>{company.email}</td>
                <td>{company.phone}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    );
  }

  get canImport() {
    const { isLoading, hasError, qboResults } = this.state;

    const finishedLoading = !isLoading;
    const noErrors = !hasError;
    const hasResults = qboResults.length > 0;

    return finishedLoading && noErrors && hasResults;
  }

  handleImportBtnClick = () => {
    this.setState(
      {
        isWaitingForImportToFinish: true
      },
      async () => {
        try {
          const { data: { companies } } = await axios.post(`/admin/company/upsert-batch`, {
            companies: this.state.qboResults
          });

          this.props.setCompanies(companies);
          this.closeBtn.click();
        } catch (e) {
          this.setState({
            isWaitingForImportToFinish: false
          });
          console.error(e);
        }
      }
    );
  };

  render() {
    const { props } = this;
    return (
      <ModalTemplate
        title="Import QuickBooks Online Companies"
        handleCloseModalClick={props.handleCloseModalClick}
        style={{ width: '50%' }}
      >
        <div className="modal-body">{this.contents}</div>
        <div className="modal-footer">
          <button
            type="button"
            ref={closeBtn => {
              this.closeBtn = closeBtn;
            }}
            className="btn btn-default"
            data-dismiss="modal"
            onClick={props.handleCloseModalClick}
          >
            Close
          </button>
          {this.canImport && (
            <button type="button" className="btn btn-primary" onClick={this.handleImportBtnClick}>
              {this.state.isWaitingForImportToFinish ? (
                <i className="fa fa-circle-o-notch fa-spin fa-fw" />
              ) : (
                <span>Continue Import</span>
              )}
            </button>
          )}
        </div>
      </ModalTemplate>
    );
  }
}

export default ImportQboModal;
