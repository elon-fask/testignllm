import moment from 'moment';
import React from 'react';
import MUIDialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import SelectField from '../../../common/components/formik/SelectField';
import OrderedSelectField from '../../../common/components/formik/OrderedSelectField';
import TextField from '../../../common/components/formik/TextField';
import Checkbox from '../../../common/components/formik/Checkbox';

const timeslots = {
  '8:00 AM': '8:00 AM',
  '9:00 AM': '9:00 AM',
  '10:00 AM': '10:00 AM',
  '11:00 AM': '11:00 AM',
  '12:00 PM': '12:00 PM',
  '1:00 PM': '1:00 PM',
  '2:00 PM': '2:00 PM',
  '3:00 PM': '3:00 PM',
  '4:00 PM': '4:00 PM',
  '5:00 PM': '5:00 PM',
  '6:00 PM': '6:00 PM',
  '7:00 PM': '7:00 PM',
  '8:00 PM': '8:00 PM'
};
const PracticalTestScheduleDialog = props => {
  const startDate = moment(props.testSession.startDate);
  const endDate = moment(props.testSession.endDate);
  const numDays = endDate.diff(startDate, 'days') + 1;

  const dayOptions = {
    1: 'Day 1',
    2: 'Day 2',
    3: 'Day 3'
  };
  const cities = ['west sacramento', 'desoto','la mirada', 'humble'];
  const [city] = props.testSession.name.split(',');
  if(cities.includes(city.toLowerCase().trim()) && props.testSession.session_number.includes('PE')) {
    dayOptions[4] = 'Day 4';
    dayOptions[5] = 'Day 5';
  } else if (numDays > 3) {
    dayOptions[4] = 'Day 4';
  }

  let practiceTimeCreditsSection = null;

  if (props.values.candidateId) {
    const candidate = props.candidateOptions.find(({ key }) => key === parseInt(props.values.candidateId, 10));

    if (candidate && candidate.practiceTimeCredits) {
      practiceTimeCreditsSection = <div>{`Unused Paid Practice Time Hours: ${candidate.practiceTimeCredits}`}</div>;
    }
  }

  return (
    <MUIDialog
      title="Add Practical Test Schedule"
      modal
      open={props.isOpen}
      autoScrollBodyContent
      actions={[
        <RaisedButton primary label="Cancel" style={{ marginRight: '20px' }} onClick={props.closeDialog} />,
        <RaisedButton primary label="Confirm" onClick={props.handleSubmit} />
      ]}
    >
      <form onSubmit={props.handleSubmit} style={{ display: 'flex', flexDirection: 'column' }}>
        <Field name="candidateId" label="Candidate" options={props.candidateOptions} component={OrderedSelectField} />
        <Field
          name="newOrRetest"
          label="New or Retest"
          options={{ NEW: 'New', RETEST: 'Retest', NONE: 'None' }}
          component={SelectField}
        />
        <Field name="day" label="Day" options={dayOptions} component={SelectField} />
        <Field name="time" label="Time" options={timeslots} component={SelectField} />
        <Field name="practiceTimeOnly" label="Practice Time Only" component={Checkbox} style={{margin: '20px 0 0'}} />
        <Field name="practiceHours" label="Practice Hours" type="number" component={TextField} />
        {practiceTimeCreditsSection}
      </form>
    </MUIDialog>
  );
};

export default withFormik({
  handleSubmit: ({ candidateId, newOrRetest, day, time, practiceHours, practiceTimeOnly }, { props }) => {
    const payload = {
      type: 'TEST',
      new_or_retest: newOrRetest,
      candidate_id: candidateId,
      day,
      time,
      practice_time_only:!!practiceTimeOnly,
      practice_hours: practiceHours
    };
    props
      .updatePracticalTestSchedule(payload)
      .then(() => {
        props.closeDialog();
      })
      .catch(e => {
        console.error(e);
      });
  },
  mapPropsToValues: props => ({
    candidateId: '',
    newOrRetest: '',
    day: '',
    time: '',
    practicaHours: ''
  }),
  validationSchema: Yup.object().shape({
    candidateId: Yup.number().required('Candidate is required.'),
    newOrRetest: Yup.string().required('New or Retest is required.'),
    day: Yup.number().required('Day is required.'),
    time: Yup.string().required('Time is required.'),
    practiceHours: Yup.string().matches(/^\d+\.?\d?\d?$/, 'Only two decimal places are allowed for Practice Hours.')
  })
})(PracticalTestScheduleDialog);
