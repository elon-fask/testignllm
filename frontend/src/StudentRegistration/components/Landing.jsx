import { Field, Form, withFormik } from 'formik';
import { Persist } from 'formik-persist';
import React from 'react';
import { apiCheckKeyword } from '../lib/api';

const LandingForm = ({ touched, errors, onChange, setFieldValue, setFieldError }) => {
  const hasInvalidKeyword = touched.keyword && errors.keyword;

  const keywordChangeHandler = event => {
    setFieldValue('keyword', event.currentTarget.value);
    if (errors.keyword) {
      setFieldError('keyword', '');
    }
  };

  return (
    <Form className="center-content-flex">
      <div
        className={`form-group ${hasInvalidKeyword ? 'has-danger' : ''}`}
        style={{ alignItems: 'center', display: 'flex', flexFlow: 'column' }}
      >
        <Field
          style={{ width: '250px' }}
          className="form-control text-center"
          placeholder="Please input password"
          name="keyword"
          onChange={keywordChangeHandler}
        />
        <div className="form-control-feedback" style={{ visibility: hasInvalidKeyword ? 'visible' : 'hidden' }}>
          {errors.keyword}
        </div>
      </div>
      <button type="submit" className="btn btn__cta">
        Submit
      </button>
      <Persist name="keyword" />
    </Form>
  );
};

const EnhancedForm = withFormik({
  handleSubmit(values, { props, setFieldError, setStatus }) {
    apiCheckKeyword(values.keyword)
      .then(({ data }) => {
        if (data.valid) {
          props.updateSection('landingSection', values);
        } else {
          throw new Error('Invalid password. Please use a different password and try again.');
        }
      })
      .catch(() => {
        setFieldError('keyword', 'Invalid password. Please use a different password and try again.');
        setStatus('apiHasError');
      });
  },
  mapPropsToValues() {
    return {
      keyword: ''
    };
  }
})(LandingForm);

const Landing = props => {
  const branding = 'ACS';

  return (
    <div className="container__main">
      <div className="content__main">
        <h4>Please call</h4>
        <h4>(888) 957-7277 to get a password for American Crane School</h4>
        <h4>- or -</h4>
        <h4>(888) 967-7277 to get a password for California Crane School</h4>
      </div>
      <div>
        <EnhancedForm updateSection={props.updateSection} />
      </div>
    </div>
  );
};

export default Landing;
