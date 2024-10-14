import axios from 'axios';
import React, { Component } from 'react';
import ModalTemplate from './ModalTemplate';

class ImportFileModal extends Component {
  state = {
    companies: [],
    isSubmitting: false
  };

  handleFileChange = async e => {
    if (e.currentTarget.files[0]) {
      const file = e.currentTarget.files[0];
      const formData = new FormData();
      formData.append(0, file);

      const { data } = await axios.post('/admin/candidates/preview-bulk-register', formData);

      if (data.table) {
        const companies = Object.keys(data.table).map(key => {
          const row = data.table[key];
          return {
            key,
            name: row['A'],
            email: row['B'],
            phone: row['C'].toString()
          };
        });

        this.setState({
          companies
        });
      }
    }
  };

  handleSubmit = () => {
    this.setState({ isSubmitting: true }, async () => {
      const companies = this.state.companies.map(({ name, email, phone }) => ({ name, email, phone }));

      const { data } = await axios.post('/admin/company/upsert-batch', { companies });
      this.props.setCompanies(data.companies);
      document.getElementById('close-btn').click();
    });
  };

  render() {
    const { props, state: { companies } } = this;
    return (
      <ModalTemplate title="Import from File" handleCloseModalClick={props.handleCloseModalClick}>
        <div className="modal-body">
          <form>
            <div className="form-group">
              <label htmlFor="field-file">
                File (Excel, CSV)
                <input type="file" className="form-control" id="field-file" onChange={this.handleFileChange} />
              </label>
            </div>
          </form>
          <div>
            <h4>Table Preview</h4>
            {companies.length > 0 ? (
              <div>
                <table className="table table-striped">
                  <thead>
                    <tr>
                      <th>Company Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                    </tr>
                  </thead>
                  <tbody>
                    {companies.map(company => (
                      <tr key={company.key}>
                        <td>{company.name}</td>
                        <td>{company.email}</td>
                        <td>{company.phone}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            ) : (
              <div>Please select a valid file to preview companies to be added.</div>
            )}
          </div>
        </div>
        <div className="modal-footer">
          <button
            id="close-btn"
            type="button"
            className="btn btn-default"
            data-dismiss="modal"
            onClick={props.handleCloseModalClick}
          >
            Close
          </button>
          <button type="button" className="btn btn-primary" onClick={this.handleSubmit}>
            {this.state.isSubmitting ? (
              <i className="fa fa-circle-o-notch fa-spin" aria-hidden="true" />
            ) : (
              <span>Submit</span>
            )}
          </button>
        </div>
      </ModalTemplate>
    );
  }
}

export default ImportFileModal;
