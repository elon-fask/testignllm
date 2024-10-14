import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { formatMoney } from 'accounting';
import TextField from '../../../common/components/formik/bootstrap/TextField';
import TextAreaField from '../../../common/components/formik/bootstrap/TextAreaField';

const AddDiscount = props => [
  <div key={0} className="modal-body">
    <h4>{`Max Discount ${formatMoney(props.maxDiscount)}`}</h4>
    <form>
      <Field type="number" name="amount" label="Amount" component={TextField} />
      <Field name="remarks" label="Remarks" component={TextAreaField} />
    </form>
  </div>,
  <div key={1} className="modal-footer">
    <button type="button" data-dismiss="modal" className="btn btn-default">
      Close
    </button>
    <button type="button" onClick={props.handleSubmit} className="btn btn-success">
      Add Discount
    </button>
  </div>
];

export default withFormik({
  handleSubmit: (values, { resetForm, props: { addTransaction } }) => {
    resetForm();
    addTransaction({ type: '30', ...values });
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
})(AddDiscount);
