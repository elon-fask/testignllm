import React, { Component, Fragment } from 'react';
import Modal from './Modal';
import axios from 'axios';

const defaultModalOptions = {
  type: 'ERROR'
};

class Main extends Component {
  state = {
    companies: this.props.companies,
    isModalOpen: false,
    modalOptions: defaultModalOptions
  };

  get companiesSection() {
    if (this.state.companies.length < 1) {
      return <div>No companies found.</div>;
    }

    return (
      <table className="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>QBO ID</th>
            <th>Company Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th />
          </tr>
        </thead>
        <tbody>
          {this.state.companies.map(company => (
            <tr key={company.id}>
              <td style={{ verticalAlign: 'middle' }}>{company.id}</td>
              <td style={{ verticalAlign: 'middle' }}>{company.qbo_id}</td>
              <td style={{ verticalAlign: 'middle' }}>{company.name}</td>
              <td style={{ verticalAlign: 'middle' }}>{company.email}</td>
              <td style={{ verticalAlign: 'middle' }}>{company.phone}</td>
              <td>
                <div>
                  <button
                    type="button"
                    className="btn btn-primary"
                    data-toggle="modal"
                    data-target="#modal"
                    data-id={company.id}
                    onClick={this.handleEditBtnClick}
                  >
                    Edit
                  </button>
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    );
  }

  setCompanies = companies => {
    this.setState({ companies });
  };

  addCompany = async ({ name, email, phone }) => {
    const { data: companies } = await axios.post(`/admin/company/add?returnAll=1`, {
      name,
      email,
      phone
    });

    this.setCompanies(companies);
  };

  updateCompany = async company => {
    const { data: updatedCompany } = await axios.post('/admin/company/update', company);

    const index = this.state.companies.findIndex(co => co.id === updatedCompany.id.toString());

    const newCompanies = [
      ...this.state.companies.slice(0, index),
      updatedCompany,
      ...this.state.companies.slice(index + 1)
    ];

    this.setState({ companies: newCompanies });
  };

  handleImportQboBtnClick = () => {
    this.setState({
      isModalOpen: true,
      modalOptions: {
        type: 'IMPORT_QBO'
      }
    });
  };

  handleImportFileBtnClick = () => {
    this.setState({
      isModalOpen: true,
      modalOptions: {
        type: 'IMPORT_FILE'
      }
    });
  };

  handleManualAddBtnClick = () => {
    this.setState({
      isModalOpen: true,
      modalOptions: {
        type: 'MANUAL_ADD'
      }
    });
  };

  handleEditBtnClick = e => {
    const { id } = e.currentTarget.dataset;
    const company = this.state.companies.find(co => co.id === id);

    this.setState({
      isModalOpen: true,
      modalOptions: {
        type: 'UPDATE',
        ...company
      }
    });
  };

  handleCloseModalClick = () => {
    this.setState({
      isModalOpen: false,
      modalOptions: defaultModalOptions
    });
  };

  render() {
    const { isModalOpen, modalOptions } = this.state;

    return (
      <Fragment>
        <div style={{ display: 'flex', justifyContent: 'space-between' }}>
          <h1>Companies</h1>
          <div style={{ display: 'flex', justifyContent: 'flex-end', alignItems: 'flex-end' }}>
            <div>
              <button
                type="button"
                className="btn btn-primary"
                onClick={this.handleManualAddBtnClick}
                style={{ marginRight: '24px' }}
                data-toggle="modal"
                data-target="#modal"
              >
                Add Manually
              </button>
              <button
                type="button"
                className="btn btn-primary"
                onClick={this.handleImportFileBtnClick}
                style={{ marginRight: '24px' }}
                data-toggle="modal"
                data-target="#modal"
              >
                Import from File
              </button>
              <button
                type="button"
                className="btn btn-primary"
                onClick={this.handleImportQboBtnClick}
                data-toggle="modal"
                data-target="#modal"
              >
                Import from QuickBooks Online
              </button>
            </div>
          </div>
        </div>
        {this.companiesSection}
        <Modal
          isModalOpen={isModalOpen}
          {...modalOptions}
          setCompanies={this.setCompanies}
          addCompany={this.addCompany}
          updateCompany={this.updateCompany}
          handleCloseModalClick={this.handleCloseModalClick}
        />
      </Fragment>
    );
  }
}

export default Main;
