import React, { Fragment } from 'react';
import { withFormik, Field } from 'formik';
import { object as YupObject, string as YupString } from 'yup';
import TextField from '../../../common/components/formik/bootstrap/TextField';

const SetPracticeTimeCreditsDialog = props => (
  <Fragment>
    <div className="modal-body">
      <form onSubmit={props.handleSubmit}>
        <Field type="number" name="credits" label="Practice Time Credits" component={TextField} />
      </form>
    </div>
    <div className="modal-footer">
      <button type="button" data-dismiss="modal" className="btn btn-default">
        Close
      </button>
      <button type="button" onClick={props.handleSubmit} className="btn btn-success">
        Confirm
      </button>
    </div>
  </Fragment>
);

export default withFormik({
  handleSubmit: ({ credits }, { props: { updatePracticeTimeCredits } }) => {
    updatePracticeTimeCredits(credits);
  },
  mapPropsToValues: props => ({
    credits: props.practiceTimeCredits
  }),
  validationSchema: YupObject().shape({
    credits: YupString().matches(/^\d+\.?\d?\d?$/, 'Only two decimal places are allowed for Practice Hours.')
  })
})(SetPracticeTimeCreditsDialog);
