import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { paymentTypes } from '../../../common/candidateTransactions';
import TextField from '../../../common/components/formik/bootstrap/TextField';
import SelectField from '../../../common/components/formik/bootstrap/SelectField';
import TextAreaField from '../../../common/components/formik/bootstrap/TextAreaField';

const ReceivePayment = props => [
  <div key={0} className="modal-body">
    <form>
      <Field options={paymentTypes} name="type" label="Type" component={SelectField} />
      <Field type="number" name="amount" label="Amount" component={TextField} />
      {props.values.type === '2' ? (
        <Field type="text" name="checkNumber" label="Check Number" component={TextField} />
      ) : (
        <Field name="remarks" label="Remarks" component={TextAreaField} />
      )}
    </form>
  </div>,
  <div key={1} className="modal-footer">
    <button type="button" data-dismiss="modal" className="btn btn-default">
      Close
    </button>
    {props.values.type === '4' && (
      <button
        type="button"
        onClick={e => {
          props.setFieldValue('willChargeAuthorizeNet', true);
          props.handleSubmit(e);
        }}
        className="btn btn-success"
      >
        Charge w/ Authorize.net
      </button>
    )}
    <button type="button" onClick={props.handleSubmit} className="btn btn-success">
      Post Payment
    </button>
  </div>
];

export default withFormik({
  handleSubmit: (values, { resetForm, props: { addTransaction, idHash } }) => {
    resetForm();

    if (values.willChargeAuthorizeNet) {
      window.location.href = `/admin/candidates/epayment?id=${idHash}&amount=${values.amount}&remarks=${
        values.remarks
      }`;
    } else {
      addTransaction(values);
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
})(ReceivePayment);
