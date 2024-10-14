import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import { chargeTypes, craneTypes } from '../../../candidateTransactions';
import TextField from '../../formik/TextField';
import SelectField from '../../formik/SelectField';

const AddChargeDialog = props => (
  <Dialog
    title="Add Charge"
    actions={[
      <RaisedButton label="Close" onClick={props.closeDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton label="Add Charge" primary onClick={props.handleSubmit} />
    ]}
    modal
    open={props.open}
  >
    <form onSubmit={props.handleSubmit} style={{ display: 'flex', flexDirection: 'column' }}>
      <Field options={chargeTypes} name="type" label="Type" component={SelectField} style={{ width: '400px' }} />
      {props.values.type === '50' && (
        <Field
          options={craneTypes}
          name="craneSelection"
          label="Crane Selection"
          component={SelectField}
          style={{ width: '400px' }}
        />
      )}
      {props.values.type === '73' ? (
        <Field type="number" name="hours" label="Number of Hours" component={TextField} style={{ width: '400px' }} />
      ) : (
        <Field type="number" name="amount" label="Amount" component={TextField} style={{ width: '400px' }} />
      )}
      <Field name="remarks" label="Remarks" component={TextField} multiLine rows={3} style={{ width: '400px' }} />
    </form>
  </Dialog>
);

export default withFormik({
  handleSubmit: (values, { resetForm, props: { createTransaction } }) => {
    const payload = values;

    if (values.type === '73') {
      payload.amount = parseFloat(values.hours) * 125;
    }

    resetForm();
    createTransaction(payload);
  },
  mapPropsToValues: () => ({
    type: '',
    craneSelection: '',
    amount: '',
    hours: '',
    remarks: ''
  }),
  validationSchema: Yup.object().shape({
    type: Yup.mixed()
      .oneOf(Object.keys(chargeTypes))
      .required('Charge Type is required.'),
    craneSelection: Yup.mixed()
      .oneOf(Object.keys(craneTypes))
      .when('type', (type, schema) => (type === '50' ? schema.required('Crane Selection is required.') : schema)),
    hours: Yup.number().when(
      'type',
      (type, schema) =>
        type === '73'
          ? schema.moreThan(0, 'Number of Hours must be greater than $0.').required('Number of Hours is required.')
          : schema
    ),
    amount: Yup.number().when(
      'type',
      (type, schema) =>
        type !== '73' ? schema.moreThan(0, 'Amount must be greater than $0.').required('Amount is required.') : schema
    ),
    remarks: Yup.string()
  })
})(AddChargeDialog);
