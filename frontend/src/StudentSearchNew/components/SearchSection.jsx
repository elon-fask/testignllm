import React, { Component } from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import moment from 'moment';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from '../../common/components/formik/TextField';
import DatePicker from '../../common/components/formik/DatePicker';
import Checkbox from '../../common/components/formik/Checkbox';

class SearchSection extends Component {
  componentDidUpdate(prevProps) {
    if (prevProps.values.certifyTwelveMonthWindow === false && this.props.values.certifyTwelveMonthWindow === true) {
      const newStartDate = moment();
      newStartDate.subtract(12, 'months');
      this.props.setFieldValue('dateCreatedStart', newStartDate.toDate());
      this.props.setFieldValue('dateCreatedEnd', moment().toDate());

      if (this.props.values.recertifyTwelveMonthWindow) {
        this.props.setFieldValue('recertifyTwelveMonthWindow', false);
      }
    }

    if (
      prevProps.values.recertifyTwelveMonthWindow === false &&
      this.props.values.recertifyTwelveMonthWindow === true
    ) {
      const newStartDate = moment();
      newStartDate.subtract(5, 'years');

      const newEndDate = moment();
      newEndDate.subtract(4, 'years');

      this.props.setFieldValue('dateCreatedStart', newStartDate.toDate());
      this.props.setFieldValue('dateCreatedEnd', newEndDate.toDate());

      if (this.props.values.certifyTwelveMonthWindow) {
        this.props.setFieldValue('certifyTwelveMonthWindow', false);
      }
    }
  }

  render() {
    const { props } = this;
    return (
      <form onSubmit={props.handleSubmit}>
        <div>
          <div style={{ fontWeight: 'bold' }}>Application Information</div>
          <div>
            <Field component={TextField} name="name" label="Name" />
          </div>
          <div>
            <Field component={TextField} name="company" label="Company" />
          </div>
          <div>
            <div>Registration Date (Start)</div>
            <Field component={DatePicker} name="dateCreatedStart" label="Created Date Range (Start)" />
          </div>
          <div>
            <div>Registration Date (End)</div>
            <Field component={DatePicker} name="dateCreatedEnd" label="Created Date Range (End)" />
          </div>
        </div>
        <div style={{ fontWeight: 'bold', marginTop: '20px' }}>Special Conditions</div>
        <div>
          <Field
            component={Checkbox}
            name="certifyTwelveMonthWindow"
            label="Within 12-month window of getting certified"
          />
          <Field component={Checkbox} name="recertifyTwelveMonthWindow" label="Needs to recertify within 12 months" />
        </div>
        <div style={{ fontWeight: 'bold', marginTop: '20px' }}>Test Information</div>
        <div style={{ display: 'flex', justifyContent: 'space-between' }}>
          <div>
            <div style={{ fontWeight: 'bold' }}>Signed Up for Exams</div>
            <Field component={Checkbox} name="signedUpWrittenCore" label="Written Core Exam" />
            <Field component={Checkbox} name="signedUpWrittenSw" label="Written Swing Cab Exam" />
            <Field component={Checkbox} name="signedUpWrittenFx" label="Written Fixed Cab Exam" />
            <Field component={Checkbox} name="signedUpPracticalSw" label="Practical Swing Cab Exam" />
            <Field component={Checkbox} name="signedUpPracticalFx" label="Practical Fixed Cab Exam" />
          </div>
          <div>
            <div style={{ fontWeight: 'bold' }}>Did Not Sign Up for Exams</div>
            <Field component={Checkbox} name="notSignedUpWrittenCore" label="Written Core Exam" />
            <Field component={Checkbox} name="notSignedUpWrittenSw" label="Written Swing Cab Exam" />
            <Field component={Checkbox} name="notSignedUpWrittenFx" label="Written Fixed Cab Exam" />
            <Field component={Checkbox} name="notSignedUpPracticalSw" label="Practical Swing Cab Exam" />
            <Field component={Checkbox} name="notSignedUpPracticalFx" label="Practical Fixed Cab Exam" />
          </div>
          <div>
            <div style={{ fontWeight: 'bold' }}>Passed Exams</div>
            <Field component={Checkbox} name="passedWrittenCore" label="Written Core Exam" />
            <Field component={Checkbox} name="passedWrittenSw" label="Written Swing Cab Exam" />
            <Field component={Checkbox} name="passedWrittenFx" label="Written Fixed Cab Exam" />
            <Field component={Checkbox} name="passedPracticalSw" label="Practical Swing Cab Exam" />
            <Field component={Checkbox} name="passedPracticalFx" label="Practical Fixed Cab Exam" />
          </div>
          <div>
            <div style={{ fontWeight: 'bold' }}>Failed Exams</div>
            <Field component={Checkbox} name="failedWrittenCore" label="Written Core Exam" />
            <Field component={Checkbox} name="failedWrittenSw" label="Written Swing Cab Exam" />
            <Field component={Checkbox} name="failedWrittenFx" label="Written Fixed Cab Exam" />
            <Field component={Checkbox} name="failedPracticalSw" label="Practical Swing Cab Exam" />
            <Field component={Checkbox} name="failedPracticalFx" label="Practical Fixed Cab Exam" />
          </div>
          <div>
            <div style={{ fontWeight: 'bold' }}>No Show, Declined Exams</div>
            <Field component={Checkbox} name="didNotTakePracticalSw" label="Practical Swing Cab Exam" />
            <Field component={Checkbox} name="didNotTakePracticalFx" label="Practical Fixed Cab Exam" />
          </div>
        </div>
        <RaisedButton label="Search" primary onClick={props.handleSubmit} />
      </form>
    );
  }
}

export default withFormik({
  handleSubmit: (values, { props }) => {
    const payload = {
      startDate: moment(values.dateCreatedStart).format('YYYY-MM-DD HH:mm:ss'),
      endDate: moment(values.dateCreatedEnd).format('YYYY-MM-DD HH:mm:ss')
    };

    if (values.name) {
      const name = values.name.split(', ');
      payload.lastName = name[0];

      if (name[1]) {
        payload.firstName = name[1];
      }
    }

    if (values.company) {
      payload.company = values.company;
    }

    if (values.certifyTwelveMonthWindow) {
      payload.certifyTwelveMonthWindow = values.certifyTwelveMonthWindow;
    }

    if (values.recertifyTwelveMonthWindow) {
      payload.recertifyTwelveMonthWindow = values.recertifyTwelveMonthWindow;
    }

    if (
      values.signedUpWrittenCore ||
      values.signedUpWrittenSw ||
      values.signedUpWrittenFx ||
      values.signedUpPracticalSw ||
      values.signedUpPracticalFx
    ) {
      const fields = [
        'signedUpWrittenCore',
        'signedUpWrittenSw',
        'signedUpWrittenFx',
        'signedUpPracticalSw',
        'signedUpPracticalFx'
      ];

      const enabledFields = fields.filter(field => values[field]);

      payload.signedUp = enabledFields;
    }

    if (
      values.notSignedUpWrittenCore ||
      values.notSignedUpWrittenSw ||
      values.notSignedUpWrittenFx ||
      values.notSignedUpPracticalSw ||
      values.notSignedUpPracticalFx
    ) {
      const fields = [
        'notSignedUpWrittenCore',
        'notSignedUpWrittenSw',
        'notSignedUpWrittenFx',
        'notSignedUpPracticalSw',
        'notSignedUpPracticalFx'
      ];

      const enabledFields = fields.filter(field => values[field]);

      payload.notSignedUp = enabledFields;
    }

    if (
      values.passedWrittenCore ||
      values.passedWrittenSw ||
      values.passedWrittenFx ||
      values.passedPracticalSw ||
      values.passedPracticalFx
    ) {
      const fields = [
        'passedWrittenCore',
        'passedWrittenSw',
        'passedWrittenFx',
        'passedPracticalSw',
        'passedPracticalFx'
      ];

      const enabledFields = fields.filter(field => values[field]);

      payload.passed = enabledFields;
    }

    if (
      values.failedWrittenCore ||
      values.failedWrittenSw ||
      values.failedWrittenFx ||
      values.failedPracticalSw ||
      values.failedPracticalFx
    ) {
      const fields = [
        'failedWrittenCore',
        'failedWrittenSw',
        'failedWrittenFx',
        'failedPracticalSw',
        'failedPracticalFx'
      ];

      const enabledFields = fields.filter(field => values[field]);

      payload.failed = enabledFields;
    }

    props.handleSearch(payload);
  },
  mapPropsToValues: () => ({
    name: '',
    company: '',
    dateCreatedStart: '',
    dateCreatedEnd: '',
    certifyTwelveMonthWindow: false,
    recertifyTwelveMonthWindow: false,
    signedUpWrittenCore: false,
    signedUpWrittenSw: false,
    signedUpWrittenFx: false,
    signedUpPracticalSw: false,
    signedUpPracticalFx: false,
    notSignedUpWrittenCore: false,
    notSignedUpWrittenSw: false,
    notSignedUpWrittenFx: false,
    notSignedUpPracticalSw: false,
    notSignedUpPracticalFx: false,
    passedWrittenCore: false,
    passedWrittenSw: false,
    passedWrittenFx: false,
    passedPracticalSw: false,
    passedPracticalFx: false,
    failedWrittenCore: false,
    failedWrittenSw: false,
    failedWrittenFx: false,
    failedPracticalSw: false,
    failedPracticalFx: false,
    didNotTakePracticalSw: false,
    didNotTakePracticalFx: false
  }),
  validationSchema: Yup.object().shape({
    dateCreatedStart: Yup.string().required('Registration Date (Start) is required.'),
    dateCreatedEnd: Yup.string().required('Registration Date (End) is required.')
  })
})(SearchSection);
