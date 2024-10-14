import React, { Component, Fragment } from 'react';
import moment from 'moment';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import Paper from 'material-ui/Paper';
import RaisedButton from 'material-ui/RaisedButton';
import CircularProgress from 'material-ui/CircularProgress';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { apiFindTestSession, apiFindCompanies } from '../../common/api';
import FieldWrapper from '../../common/components/FieldWrapper';
import TextField from '../../common/components/formik/TextField';
import Checkbox from '../../common/components/formik/Checkbox';
import SelectField from '../../common/components/formik/SelectField';
import DatePicker from '../../common/components/formik/DatePicker';
import AutoComplete from '../../common/components/formik/AutoComplete';

const theme = getMuiTheme({
  palette: {
    primary1Color: '#0471af',
    primary2Color: '#0471af',
    accent1Color: '#0471af',
    pickerHeaderColor: '#0471af'
  }
});

const reportTypes = {
  CLASSREADINESS: 'Class Readiness Report',
  CANDIDATE_CHECKLIST: 'Candidate Checklist / Unsigned Applications Report',
  PRACTICAL_TEST_SCHEDULE: 'Practical Test Schedule',
  GRADING: 'Pass / Fail Report',
  BOOKKEEPING: 'Bookkeeping Report',
  BOOKKEEPING_BACKLOG: 'Bookkeeping (Reduced) Report',
  DECLINED_TEST: 'Declined Practical Exam Report',
  END_OF_YEAR_REVIEW: 'End of the Year Review Report',
  END_OF_YEAR_ENROLLMENTS: 'End of the Year Enrollments Report'
};

class Main extends Component {
  constructor(props) {
    super(props);

    this.state = {
      testSessions: {},
      companySuggestions: [],
      isLoading: false
    };
  }

  componentDidUpdate(prevProps) {
    try {
      const datesValid =
        Object.prototype.toString.call(this.props.values.startDate) === '[object Date]' &&
        Object.prototype.toString.call(this.props.values.endDate) === '[object Date]';

      const startDateChanged =
        !prevProps.values.startDate || this.props.values.startDate.getTime() !== prevProps.values.startDate.getTime();
      const endDateChanged =
        !prevProps.values.endDate || this.props.values.endDate.getTime() !== prevProps.values.endDate.getTime();

      if (datesValid && (startDateChanged || endDateChanged)) {
        const startDate = moment(this.props.values.startDate).format('YYYY-MM-DD');
        const endDate = moment(this.props.values.endDate).format('YYYY-MM-DD');
        this.setState(
          {
            isLoading: true
          },
          () => {
            if (this.props.values.type === 'DECLINED_TEST') {
              let testSessionData = [];

              apiFindTestSession(startDate, endDate)
                .then(({ data }) => {
                  testSessionData = data;
                  const testSessionIds = testSessionData.map(testSession => testSession.id);
                  return apiFindCompanies(testSessionIds);
                })
                .then(({ data }) => {
                  this.setState({
                    companySuggestions: data,
                    testSessions: testSessionData.reduce(
                      (acc, testSession) => ({
                        ...acc,
                        [testSession.id]: `(${testSession.type}) : ${testSession.desc}`
                      }),
                      {}
                    ),
                    isLoading: false
                  });
                });
            } else {
              apiFindTestSession(startDate, endDate).then(({ data }) => {
                this.setState({
                  testSessions: data.reduce(
                    (acc, testSession) => ({
                      ...acc,
                      [testSession.id]: `(${testSession.type}) : ${testSession.desc}`
                    }),
                    {}
                  ),
                  isLoading: false
                });
              });
            }
          }
        );
      }
    } catch (e) {
      console.error(e);
    }
  }

  render() {
    return (
      <MuiThemeProvider muiTheme={theme}>
        <Paper>
          <div style={{ textAlign: 'center', paddingTop: '50px' }}>
            <h3>Reports</h3>
          </div>
          <section style={{ display: 'flex', justifyContent: 'center', paddingBottom: '20px' }}>
            <form
              ref={form => {
                this.form = form;
              }}
              onSubmit={this.props.handleSubmit}
            >
              <FieldWrapper label="Type">
                <Field name="type" label="" options={reportTypes} component={SelectField} style={{ width: '540px' }} />
              </FieldWrapper>
              {!['END_OF_YEAR_REVIEW', 'END_OF_YEAR_ENROLLMENTS'].includes(this.props.values.type) ? (
                <Fragment>
                  <FieldWrapper label="Start Date">
                    <Field name="startDate" hintText="Select Date" component={DatePicker} style={{ width: '540px' }} />
                  </FieldWrapper>
                  <FieldWrapper label="End Date">
                    <Field name="endDate" hintText="Select Date" component={DatePicker} style={{ width: '540px' }} />
                  </FieldWrapper>
                </Fragment>
              ) : (
                <Fragment>
                  <FieldWrapper label="Year">
                    <Field name="year" component={TextField} style={{ width: '540px' }} />
                  </FieldWrapper>
                </Fragment>
              )}
              {this.state.isLoading ? (
                <FieldWrapper label={<CircularProgress size={30} />} />
              ) : (
                <Fragment>
                  {Object.keys(this.state.testSessions).length > 0 && (
                    <FieldWrapper label="Test Session">
                      <Field
                        name="testSessionId"
                        label=""
                        options={this.state.testSessions}
                        component={SelectField}
                        style={{ width: '540px' }}
                        nullable={
                          this.props.values.type === 'BOOKKEEPING' || this.props.values.type === 'BOOKKEEPING_BACKLOG'
                        }
                      />
                    </FieldWrapper>
                  )}
                  {this.props.values.type === 'DECLINED_TEST' && (
                    <FieldWrapper label="Company">
                      <Field
                        name="company"
                        label=""
                        component={AutoComplete}
                        style={{ width: '540px' }}
                        dataSource={this.state.companySuggestions}
                      />
                    </FieldWrapper>
                  )}
                </Fragment>
              )}
              <div style={{ display: 'flex', alignItems: 'center' }}>
                <RaisedButton
                  style={{ marginTop: '20px', marginLeft: '160px', marginRight: '40px' }}
                  label="Generate"
                  primary
                  onClick={this.props.handleSubmit}
                />
                <div style={{ width: '240px', marginTop: '20px' }}>
                  <Field label="Open report in a new tab" name="newTab" component={Checkbox} />
                </div>
              </div>
            </form>
          </section>
        </Paper>
      </MuiThemeProvider>
    );
  }
}

export default withFormik({
  /* eslint-disable no-lonely-if */
  handleSubmit: (values, { props }) => {
    if (values.type === 'END_OF_YEAR_REVIEW' || values.type === 'END_OF_YEAR_ENROLLMENTS') {
      const options = encodeURI(`options[year]=${values.year}`);
      window.location.href = `/admin/reports/generate?type=${values.type}&${options}`;
      return;
    }

    if (values.type === 'DECLINED_TEST') {
      const companyQuery = values.company ? `&company=${encodeURI(values.company)}` : '';
      if (values.testSessionId) {
        props.generateReport(
          `/admin/testsession/download-declined-test-attestations?id=${values.testSessionId}${companyQuery}`,
          values.newTab
        );
      } else {
        const startDate = moment(values.startDate).format('YYYY-MM-DD');
        const endDate = moment(values.endDate).format('YYYY-MM-DD');

        props.generateReport(
          `/admin/testsession/download-declined-test-attestations?startDate=${startDate}&endDate=${endDate}${companyQuery}`,
          values.newTab
        );
      }
    } else {
      if (values.testSessionId) {
        props.generateReport(
          `/admin/testsession/spreadsheet?id=${values.testSessionId}&view=${values.type}&partial=1`,
          values.newTab
        );
      } else {
        const startDate = moment(values.startDate).format('YYYY-MM-DD');
        const endDate = moment(values.endDate).format('YYYY-MM-DD');

        props.generateReport(
          `/admin/testsession/spreadsheet?startDate=${startDate}&endDate=${endDate}&view=${values.type}&partial=1`,
          values.newTab
        );
      }
    }
  },
  mapPropsToValues: () => ({
    type: null,
    startDate: null,
    endDate: null,
    testSessionId: null,
    newTab: false,
    company: null
  }),
  validationSchema: Yup.object().shape({
    type: Yup.mixed()
      .oneOf(Object.keys(reportTypes), 'Please select a report type.')
      .required('Please select a report type.'),
    year: Yup.number().when('type', (type, schema) => {
      if (type === 'END_OF_YEAR_REVIEW' || type === 'END_OF_YEAR_ENROLLMENTS') {
        return schema.required('Please enter a year.');
      }
      return schema;
    }),
    startDate: Yup.mixed().when('type', (type, schema) => {
      if (!['END_OF_YEAR_REVIEW', 'END_OF_YEAR_ENROLLMENTS'].includes(type)) {
        return schema.required('Please select a start date.');
      }
      return schema;
    }),
    endDate: Yup.mixed().when('type', (type, schema) => {
      if (!['END_OF_YEAR_REVIEW', 'END_OF_YEAR_ENROLLMENTS'].includes(type)) {
        return schema.required('Please select an end date.');
      }
      return schema;
    }),
    testSessionId: Yup.mixed().when(
      'type',
      (type, schema) =>
        type === 'BOOKKEEPING' ||
        type === 'BOOKKEEPING_BACKLOG' ||
        type === 'DECLINED_TEST' ||
        type === 'END_OF_YEAR_REVIEW' ||
        type === 'END_OF_YEAR_ENROLLMENTS'
          ? schema
          : schema.required('Please select a Test Session.')
    ),
    company: Yup.string().nullable()
  })
})(Main);
