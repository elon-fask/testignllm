import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import ModalTemplate from './ModalTemplate';
import TextField from '../../../common/components/formik/bootstrap/TextField';

function ManualAddModal(props) {
  return (
    <ModalTemplate title="Add Manually" handleCloseModalClick={props.handleCloseModalClick}>
      <div className="modal-body">
        <form onSubmit={props.handleSubmit}>
          <Field name="name" label="Company Name" component={TextField} />
          <Field name="email" label="Email" component={TextField} />
          <Field name="phone" label="Phone" component={TextField} />
        </form>
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
        <button type="button" className="btn btn-primary" onClick={props.handleSubmit}>
          Save
        </button>
      </div>
    </ModalTemplate>
  );
}

export default withFormik({
  mapPropsToValues: () => ({
    name: '',
    email: '',
    phone: ''
  }),
  validationSchema: Yup.object().shape({
    name: Yup.string().required('Company name is required.'),
    email: Yup.string().email('Email must be a valid email address.'),
    phone: Yup.string()
  }),
  handleSubmit: async (company, { props }) => {
    await props.addCompany(company);
    document.getElementById('close-btn').click();
  }
})(ManualAddModal);
