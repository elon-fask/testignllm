import React from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import TextAreaField from '../../../common/components/formik/bootstrap/TextAreaField';

const UpdateRemarks = props => [
  <div key={0} className="modal-body">
    <form>
      <Field name="remarks" label="Remarks" component={TextAreaField} />
    </form>
  </div>,
  <div key={1} className="modal-footer">
    <button type="button" onClick={props.handleCloseDialog} data-dismiss="modal" className="btn btn-default">
      Close
    </button>
    <button type="button" onClick={props.handleSubmit} className="btn btn-success">
      Update Remarks
    </button>
  </div>
];

export default withFormik({
  handleSubmit: (values, { resetForm, props: { updateRemark, currentTransactionId } }) => {
    resetForm();
    updateRemark(currentTransactionId, values.remarks);
  },
  mapPropsToValues: ({ editingRemark }) => ({
    remarks: editingRemark || ''
  }),
  validationSchema: props =>
    Yup.object().shape({
      remarks: Yup.string()
    })
})(UpdateRemarks);
