import React from 'react';
import axios from 'axios';
import { Field, withFormik } from 'formik';
import Yup from 'yup';
import moment from 'moment';
import { LOCATIONS, TIMES, YESNO } from '../lib/constants';
import TextField from './form/TextField';
import FileField from './form/FileField';
import ToggleField from './form/ToggleField';
import SelectField from './form/SelectField';
import DateField from './form/DateField';
import TextAreaField from './form/TextAreaField';
import SubmitMessage from './form/SubmitMessage';
import '../styles/App.css';

const downloadFile = (id, filename) => {
  axios.get(`/admin/travel-form/download-file?id=${id}`).then(({ data }) => {
    console.log(data);
    window.location.href = data.result;
  });
};

const App = props => {
  const { values } = props;
  values.destLoc = values.destination === 'Other' ? values.destinationOther : values.destination;
  const destDate = moment(values.destinationDepartDate);
  values.destDate = destDate.isValid() ? destDate.format('YYYY-MM-DD') : '';

  if (!values.isOneWay) {
    values.retLoc = values.returnLoc === 'Other' ? values.returnOther : values.returnLoc;
    const retDate = moment(values.returnDepartDate);
    values.retDate = retDate.isValid() ? retDate.format('YYYY-MM-DD') : '';
  }

  return (
    <main>
      <section className="section">
        <div className="container">
          <h1 className="title">Crane School Travel Form Update</h1>
          <form onSubmit={props.handleSubmit}>
            <div className="columns">
              <div className="column">
                <Field name="name" label="Name" component={TextField} />
                <Field name="isOneWay" label="One Way Travel Only" component={ToggleField} />
                <Field name="startingLocation" label="Starting Airport Location" component={TextField} />
              </div>
            </div>
            <div className="columns is-mobile">
              <div className="column" style={{ backgroundColor: '#209cee', borderRadius: '5px 0 0 5px' }}>
                <Field name="destination" label="Destination Location" options={LOCATIONS} component={SelectField} />
                {props.values.destination === 'Other' && (
                  <Field name="destinationOther" label="Specify Destination" component={TextField} fadeIn />
                )}
                <Field name="destinationDepartDate" label="Dest. Depart Date" component={DateField} />
                <Field name="destinationDepartTime" label="Dest. Depart Time" options={TIMES} component={SelectField} />
              </div>
              {!props.values.isOneWay && (
                <div className="column" style={{ backgroundColor: '#00d1b2', borderRadius: '0 5px 5px 0' }}>
                  <Field name="returnLoc" label="Return Location" options={LOCATIONS} component={SelectField} />
                  {props.values.return === 'Other' && (
                    <Field name="returnOther" label="Specify Return" component={TextField} fadeIn />
                  )}
                  <Field name="returnDepartDate" label="Ret. Depart Date" component={DateField} />
                  <Field name="returnDepartTime" label="Ret. Depart Time" options={TIMES} component={SelectField} />
                </div>
              )}
            </div>
            <div className="columns is-mobile">
              <div className="column">
                <Field name="hotelRequired" label="Hotel Required" options={YESNO} component={SelectField} />
              </div>
              <div className="column">
                <Field name="carRentalRequired" label="Car Rental Required" options={YESNO} component={SelectField} />
              </div>
            </div>
            <div className="columns">
              <div className="column">
                <Field name="comment" label="Comment" rows={4} component={TextAreaField} />
              </div>
            </div>
            <div className="columns">
              <div className="column">
                <Field name="notes" label="Notes" component={TextAreaField} />
              </div>
            </div>
            <label className="label">File Attachments</label>
            <div className="columns">
              <div className="column">
                <Field name="fileUpload" id="file-upload-field" label="File Uploads" component={FileField} />
              </div>
              {props.initialState.files.length > 0 && (
                <div className="column">
                  {props.initialState.files.map(file => (
                    <div key={file.id} style={{ marginBottom: '8px' }}>
                      <button
                        type="button"
                        onClick={() => {
                          downloadFile(file.id, file.filename);
                        }}
                        className="button is-link is-outlined"
                      >
                        <i className="fa fa-cloud-download" aria-hidden="true" />&nbsp; {file.filename}
                      </button>
                    </div>
                  ))}
                </div>
              )}
            </div>
            <div className="columns">
              <div className="column">
                <Field name="completed" label="Completed" component={ToggleField} />
              </div>
            </div>
            <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
              <a href="/admin/travel-form/index" className="button is-link" style={{ marginRight: '20px' }}>
                Back
              </a>
              <button type="submit" className={`button is-success ${props.isSubmitting && 'is-loading'}`}>
                Save
              </button>
            </div>
          </form>
          <br />
          {props.status && <SubmitMessage status={props.status} />}
        </div>
      </section>
      <div style={{ display: 'none' }}>
        <form
          id="setup-form"
          name="setup-form"
          method="POST"
          encType="multipart/form-data"
          action={`/admin/travel-form/update?id=${props.initialState.id}`}
        >
          <input readOnly type="text" name="TravelForm[name]" value={values.name} />
          <input readOnly type="number" name="TravelForm[one_way]" value={values.isOneWay ? 1 : 0} />
          <input readOnly type="text" name="TravelForm[starting_location]" value={values.startingLocation} />
          <input readOnly type="text" name="TravelForm[destination_loc]" value={values.destLoc} />
          <input readOnly type="text" name="TravelForm[destination_date]" value={values.destDate} />
          <input readOnly type="text" name="TravelForm[destination_time]" value={values.destinationDepartTime} />
          {!values.isOneWay && [
            <input key={0} readOnly type="text" name="TravelForm[return_loc]" value={values.retLoc} />,
            <input key={1} readOnly type="text" name="TravelForm[return_date]" value={values.retDate} />,
            <input key={2} readOnly type="text" name="TravelForm[return_time]" value={values.returnDepartTime} />
          ]}
          <input readOnly type="number" name="TravelForm[hotel_required]" value={values.hotelRequired ? 1 : 0} />
          <input readOnly type="number" name="TravelForm[car_rental_required]" value={values.hotelRequired ? 1 : 0} />
          <textarea readOnly name="TravelForm[comment]" value={values.comment} />
          <textarea readOnly name="TravelForm[notes]" value={values.notes} />
          <input readOnly type="number" name="TravelForm[completed]" value={values.completed ? 1 : 0} />
        </form>
      </div>
    </main>
  );
};

/* eslint-disable camelcase */
export default withFormik({
  handleSubmit: () => {
    document.getElementById('setup-form').appendChild(document.getElementById('file-upload-field'));
    document.getElementById('setup-form').submit();
  },
  mapPropsToValues: ({
    initialState: {
      name,
      one_way,
      starting_location,
      destination_loc,
      destination_date,
      destination_time,
      return_loc,
      return_date,
      return_time,
      hotel_required,
      car_rental_required,
      comment,
      notes,
      completed
    }
  }) => {
    const isOneWay = !!one_way;
    const destinationIsStandard = LOCATIONS.includes(destination_loc);
    let returnLoc = '';
    let returnOther = '';
    let returnDepartDate = '';
    if (!isOneWay) {
      const returnIsStandard = LOCATIONS.includes(return_loc);
      returnLoc = returnIsStandard ? return_loc : 'Other';
      returnOther = returnIsStandard ? '' : return_loc;
      returnDepartDate = return_date ? moment(return_date, 'YYYY-MM-DD').toDate() : '';
    }

    return {
      name,
      isOneWay,
      startingLocation: starting_location || '',
      destination: destinationIsStandard ? destination_loc : 'Other',
      destinationOther: destinationIsStandard ? '' : destination_loc,
      destinationDepartDate: moment(destination_date, 'YYYY-MM-DD').toDate(),
      destinationDepartTime: destination_time || '',
      returnLoc,
      returnOther,
      returnDepartDate,
      returnDepartTime: return_time || '',
      hotelRequired: hotel_required ? 'Yes' : 'No',
      carRentalRequired: car_rental_required ? 'Yes' : 'No',
      comment: comment || '',
      notes: notes || '',
      fileUpload: '',
      completed: !!completed
    };
  },
  validationSchema: Yup.object().shape({
    name: Yup.string().required('Field is required.'),
    isOneWay: Yup.boolean(),
    startingLocation: Yup.string(),
    destination: Yup.mixed()
      .oneOf(LOCATIONS)
      .required('Field is required.'),
    destinationOther: Yup.string().when('destination', {
      is: 'Other',
      then: Yup.string().required('Field is required.'),
      otherwise: Yup.string()
    }),
    destinationDepartDate: Yup.date().required('Field is required.'),
    destinationDepartTime: Yup.mixed()
      .oneOf(TIMES)
      .required('Field is required.'),
    returnLoc: Yup.mixed()
      .oneOf(LOCATIONS)
      .when('isOneWay', (isOneWay, schema) => (isOneWay ? schema : schema.required('Field is required.'))),
    returnOther: Yup.string().when(['returnLoc', 'isOneWay'], {
      is: (returnLoc, isOneWay) => !isOneWay && returnLoc === 'Other',
      then: Yup.string().required('Field is required.'),
      otherwise: Yup.string()
    }),
    returnDepartDate: Yup.date()
      .when('destinationDepartDate', (destinationDepartDate, schema) => {
        if (Object.prototype.toString.call(destinationDepartDate) === '[object Date]') {
          return schema.min(
            moment(destinationDepartDate)
              .add(1, 'd')
              .toDate(),
            'Return depart date must not be on the same day as, or earlier than destination depart date.'
          );
        }
        return schema;
      })
      .when('isOneWay', (isOneWay, schema) => (isOneWay ? schema : schema.required('Field is required.'))),
    returnDepartTime: Yup.mixed()
      .oneOf(TIMES)
      .when('isOneWay', (isOneWay, schema) => (isOneWay ? schema : schema.required('Field is required.'))),
    hotelRequired: Yup.mixed()
      .oneOf(YESNO)
      .required('Field is required.'),
    carRentalRequired: Yup.mixed()
      .oneOf(YESNO)
      .required('Field is required.'),
    comment: Yup.string().max(2000, 'Comments must be limited to 2000 characters only.'),
    notes: Yup.string().max(255, 'Notes must be limited to 255 characters only.'),
    completed: Yup.boolean()
  })
})(App);
/* eslint-enable camelcase */
