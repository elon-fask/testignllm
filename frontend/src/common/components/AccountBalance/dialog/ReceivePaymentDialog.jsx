import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import { paymentTypes } from '../../../candidateTransactions';
import TextField from '../../formik/TextField';
import SelectField from '../../formik/SelectField';

const ReceivePaymentDialog = props => (
  <Dialog
    title="Receive Payment"
    actions={[
      <RaisedButton label="Close" onClick={props.closeDialog} style={{ marginRight: '20px' }} />,
      props.values.type === '4' && (
        <RaisedButton
          onClick={e => {
            props.setFieldValue('willChargeAuthorizeNet', true);
            props.handleSubmit(e);
          }}
          label="Charge w/ Authorize.net"
          style={{ marginRight: '20px' }}
        />
      ),
      <RaisedButton label="Post Payment" primary onClick={props.handleSubmit} />
    ]}
    modal
    open={props.open}
  >
    <form onSubmit={props.handleSubmit} style={{ display: 'flex', flexDirection: 'column' }}>
      <Field options={paymentTypes} name="type" label="Type" component={SelectField} style={{ width: '400px' }} />
      <Field type="number" name="amount" label="Amount" component={TextField} style={{ width: '400px' }} />
      {props.values.type === '2' ? (
        <Field type="text" name="checkNumber" label="Check Number" component={TextField} style={{ width: '400px' }} />
      ) : (
        <Field name="remarks" label="Remarks" component={TextField} multiLine rows={3} style={{ width: '400px' }} />
      )}
    </form>
  </Dialog>
);

export default withFormik({
  handleSubmit: (values, { resetForm, props: { createTransaction, idHash } }) => {
    resetForm();

    if (values.willChargeAuthorizeNet) {
      window.location.href = `/admin/candidates/epayment?id=${idHash}&amount=${values.amount}&remarks=${
        values.remarks
      }`;
    } else {
      createTransaction(values);
    }
  },
  mapPropsToValues: () => ({
    type: '',
    amount: '',
    checkNumber: '',
    remarks: '',
    willChargeAuthorizeNet: false
  }),
  validationSchema: Yup.object().shape({
    type: Yup.mixed()
      .oneOf(Object.keys(paymentTypes))
      .required('Payment Type is required.'),
    amount: Yup.number()
      .moreThan(0, 'Amount must be greater than $0.')
      .required('Amount is required.'),
    checkNumber: Yup.string(),
    remarks: Yup.string(),
    willChargeAuthorizeNet: Yup.boolean()
  })
})(ReceivePaymentDialog);
