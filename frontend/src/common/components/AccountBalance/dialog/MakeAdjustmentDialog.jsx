import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from '../../formik/TextField';

const MakeAdjustmentDialog = props => (
  <Dialog
    title="Make an Adjustment"
    actions={[
      <RaisedButton label="Close" onClick={props.closeDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton label="Make Adjustment" primary onClick={props.handleSubmit} />
    ]}
    modal
    open={props.open}
  >
    <form onSubmit={props.handleSubmit} style={{ display: 'flex', flexDirection: 'column' }}>
      <Field type="number" name="amount" label="Amount" component={TextField} style={{ width: '400px' }} />
      <Field name="remarks" label="Remarks" component={TextField} multiLine rows={3} style={{ width: '400px' }} />
    </form>
  </Dialog>
);

export default withFormik({
  handleSubmit: (values, { resetForm, props: { createTransaction } }) => {
    resetForm();
    createTransaction({ type: '31', ...values });
  },
  mapPropsToValues: () => ({
    amount: '',
    remarks: ''
  }),
  validationSchema: Yup.object().shape({
    amount: Yup.number().required('Amount is required.'),
    remarks: Yup.string()
  })
})(MakeAdjustmentDialog);
