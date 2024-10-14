import React from 'react';
import MUIDialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import TextField from '../../../common/components/formik/TextField';

const TestFeesCreditDialog = props => (
  <MUIDialog
    title="NCCCO Test Fees Credit"
    modal
    open={props.isOpen}
    actions={[
      <RaisedButton primary label="Cancel" style={{ marginRight: '20px' }} onClick={props.closeDialog} />,
      <RaisedButton primary label="Confirm" onClick={props.handleSubmit} />
    ]}
  >
    <form onSubmit={props.handleSubmit}>
      <Field name="amount" type="number" label="Credit Amount" component={TextField} />
    </form>
  </MUIDialog>
);

export default withFormik({
  handleSubmit: (values, { props }) => {
    props
      .setNcccoTestFeesCredit(values.amount)
      .then(() => {
        props.closeDialog();
      })
      .catch(e => {
        console.error(e);
      });
  },
  mapPropsToValues: props => ({
    amount: props.data.amount || ''
  }),
  validationSchema: Yup.object().shape({
    amount: Yup.number('Amount must be a number')
  })
})(TestFeesCreditDialog);
