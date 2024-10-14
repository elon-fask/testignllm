import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { chargeTypes, craneTypes } from '../../../common/candidateTransactions';
import TextField from '../../../common/components/formik/bootstrap/TextField';
import SelectField from '../../../common/components/formik/bootstrap/SelectField';
import TextAreaField from '../../../common/components/formik/bootstrap/TextAreaField';

const AddCharge = props => [
  <div key={0} className="modal-body">
    <form>
      <Field options={chargeTypes} name="type" label="Type" component={SelectField} />
      {props.values.type === '50' && (
        <Field options={craneTypes} name="craneSelection" label="Crane Selection" component={SelectField} />
      )}
      <Field type="number" name="amount" label="Amount" component={TextField} />
      <Field name="remarks" label="Remarks" component={TextAreaField} />
    </form>
  </div>,
  <div key={1} className="modal-footer">
    <button type="button" data-dismiss="modal" className="btn btn-default">
      Close
    </button>
    <button type="button" onClick={props.handleSubmit} className="btn btn-success">
      Add Charge
    </button>
  </div>
];

export default withFormik({
  handleSubmit: (values, { resetForm, props: { addTransaction } }) => {
    resetForm();
    addTransaction(values);
  },
  mapPropsToValues: () => ({
    type: '',
    craneSelection: '',
    amount: '',
    remarks: ''
  }),
  validationSchema: Yup.object().shape({
    type: Yup.mixed()
      .oneOf(Object.keys(chargeTypes))
      .required('Charge Type is required.'),
    craneSelection: Yup.mixed()
      .oneOf(Object.keys(craneTypes))
      .when('type', (type, schema) => (type === '50' ? schema.required('Crane Selection is required.') : schema)),
    amount: Yup.number()
      .moreThan(0, 'Amount must be greater than $0.')
      .required('Amount is required.'),
    remarks: Yup.string()
  })
})(AddCharge);
