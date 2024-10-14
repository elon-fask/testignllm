import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { formatMoney } from 'accounting';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from '../../formik/TextField';

const AddDiscountDialog = props => (
  <Dialog
    title="Add Discount"
    actions={[
      <RaisedButton label="Close" onClick={props.closeDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton label="Add Discount" primary onClick={props.handleSubmit} />
    ]}
    modal
    open={props.open}
  >
    <h4>{`Max Discount ${formatMoney(props.maxDiscount)}`}</h4>
    <form onSubmit={props.handleSubmit} style={{ display: 'flex', flexDirection: 'column' }}>
      <Field type="number" name="amount" label="Amount" component={TextField} style={{ width: '400px' }} />
      <Field name="remarks" label="Remarks" component={TextField} multiLine rows={3} style={{ width: '400px' }} />
    </form>
  </Dialog>
);

export default withFormik({
  handleSubmit: (values, { resetForm, props: { createTransaction } }) => {
    resetForm();
    createTransaction({ type: '30', ...values });
  },
  mapPropsToValues: () => ({
    amount: '',
    remarks: ''
  }),
  validationSchema: props =>
    Yup.object().shape({
      amount: Yup.number()
        .max(props.maxDiscount, `Amount must be less than or equal to ${formatMoney(props.maxDiscount)}.`)
        .required('Amount is required.'),
      remarks: Yup.string()
    })
})(AddDiscountDialog);
