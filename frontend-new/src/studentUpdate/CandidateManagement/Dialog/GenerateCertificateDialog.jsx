import React, { Fragment } from 'react';
import { withFormik, Field } from 'formik';
import { getClassDates } from '../../../common/testSession';
import TextField from '../../../common/components/formik/bootstrap/TextField';

const GenerateCertificateDialog = props => (
  <Fragment>
    <div className="modal-body">
      <form onSubmit={props.handleSubmit}>
        <Field name="instructor" label="Instructor" component={TextField} />
        <Field name="classDates" label="Class Dates" component={TextField} />
      </form>
    </div>
    <div className="modal-footer">
      <button type="button" data-dismiss="modal" className="btn btn-primary">
        Close
      </button>
      <button type="button" onClick={props.handleSubmit} className="btn btn-success">
        Download
      </button>
    </div>
  </Fragment>
);

export default withFormik({
  mapPropsToValues: props => ({
    instructor: props.candidate.instructor,
    classDates: getClassDates(props.candidate.classStartDate, props.candidate.classEndDate)
  }),
  handleSubmit: ({ instructor, classDates }, { props }) => {
    props.downloadCert(instructor, classDates);
  }
})(GenerateCertificateDialog);
