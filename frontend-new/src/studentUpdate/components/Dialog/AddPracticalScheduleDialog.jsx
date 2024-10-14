import React, { Fragment } from 'react';
import { Field, withFormik } from 'formik';
import SelectField from '../../../common/components/formik/bootstrap/SelectField';

const days = {
  1: 'Day 1',
  2: 'Day 2',
  3: 'Day 3'
};

const AddPracticalScheduleDialog = () => (
  <Fragment>
    <div className="modal-body">
      <form>
        <Field name="day" label="Day" options={days} component={SelectField} />
      </form>
    </div>
    <div className="modal-footer">
      <button type="button" data-dismiss="modal" className="btn btn-primary">
        Close
      </button>
    </div>
  </Fragment>
);

export default withFormik({
  mapPropsToValues: () => ({
    day: 1,
    time: '8:00 AM',
    crane: 'FX',
    type: 'TEST'
  })
})(AddPracticalScheduleDialog);
