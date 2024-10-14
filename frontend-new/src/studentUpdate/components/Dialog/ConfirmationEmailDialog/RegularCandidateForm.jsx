import React, { Fragment, useState, useEffect } from 'react';
import styled from 'styled-components';
import { Formik, Field } from 'formik';
import RichTextEditor from 'react-rte';
import CheckboxField from '../../../../common/components/formik/bootstrap/CheckboxField';
import EditableTemplate from './EditableTemplate';
import CommonFormElements from './CommonFormElements';
import EmailEditor from './EmailEditor';
import {
  getSalutationText,
  getRegularWrittenText,
  getRegularPracticalText,
  getSignatureBlockText
} from './TextTemplates';

const TextContainer = styled.div`
  display: flex-inline;
  align-items: center;
  line-height: 28px;
`;

function RegularCandidateForm(props) {
  const [areDetailsVisible, setAreDetailsVisible] = useState(true);

  return (
    <CommonFormElements confirmAction={props.handleSubmit} confirmDisabled={false}>
      <div>
        <Fragment>
          <div style={{ display: 'flex', alignItems: 'center' }}>
            <Field name="sendEmail" label="Send confirmation email" component={CheckboxField} />
            <button
              type="button"
              className="btn btn-primary"
              style={{ marginLeft: '8px' }}
              onClick={() => {
                setAreDetailsVisible(!areDetailsVisible);
              }}
            >
              {areDetailsVisible ? 'Hide Details' : 'Show Details'}
            </button>
          </div>
          {areDetailsVisible && (
            <div>
              <TextContainer>
                Email: <Field name="email" component={EditableTemplate} />
              </TextContainer>
              <TextContainer>
                Subject: <Field name="subject" component={EditableTemplate} />
              </TextContainer>
              <div style={{ marginTop: '24px' }}>Body:</div>
              <EmailEditor editorState={props.editorState} setEditorState={props.setEditorState} />
            </div>
          )}
        </Fragment>
      </div>
    </CommonFormElements>
  );
}

function EnhancedCandidateRegularForm(props) {
  const {
    candidate: { name, email, testSite, practicalTestSite, classDatesStr, branding, sender }
  } = props;

  const classSchedule = props.candidate.classDatesArr.map((dayStr, i) => {
    if (i === props.candidate.classDatesArr.length - 1) {
      return `${dayStr}: 8:15 am - 12:00 pm: Written Exam`;
    }

    return `${dayStr}: 8:15 am - 5:00 pm: Classroom learning`;
  });
  
  const initialValues = {
    sendEmail: true,
    email,
    classSchedule,
    subject: `Confirmation for Class Students - Upcoming Class in ${testSite.city} ${classDatesStr}`,
    name,
    classDates: classDatesStr,
    startTime: '8:00 AM',
    siteName: testSite.name,
    address: testSite.address,
    city: testSite.city,
    state: testSite.state,
    zip: testSite.zip,
    senderName: sender.name,
    school: branding === 'ACS' ? 'American Crane School' : 'California Crane School',
    senderAddress: '111 Bank St. #254',
    senderCity: 'Grass Valley',
    senderState: 'CA',
    senderZip: '95945',
    senderPhone: branding === 'ACS' ? '(888) 957-PASS (7277)' : '(888) 967-PASS (7277)',
    senderFax: '(888) 701-7277',
    senderEmail: branding === 'ACS' ? 'pass@americancraneschool.com' : 'pass@californiacraneschool.com'
  };

  if (practicalTestSite) {
    initialValues.hasPractical = true;
    initialValues.peSiteName = practicalTestSite.name;
    initialValues.peAddress = practicalTestSite.address;
    initialValues.peCity = practicalTestSite.city;
    initialValues.peState = practicalTestSite.state;
    initialValues.peZip = practicalTestSite.zip;
  }

  const [editorState, setEditorState] = useState(RichTextEditor.createValueFromString('', 'html'));

  useEffect(() => {
    const editorValue = `${getSalutationText(initialValues)}${getRegularWrittenText(
      initialValues
    )}${getRegularPracticalText(initialValues)}${getSignatureBlockText(initialValues)}`;
    const initialState = RichTextEditor.createValueFromString(editorValue, 'html');
    setEditorState(initialState);
  }, []);

  return (
    <Formik
      initialValues={{ sendEmail: true, email, subject: initialValues.subject }}
      onSubmit={values => {
        if (values.sendEmail) {
          props.confirmAction(false, {
            ...values,
            branding: props.candidate.branding,
            body: editorState.toString('html')
          });
        } else {
          props.confirmAction(false);
        }
      }}
      render={formikProps => {
        return <RegularCandidateForm editorState={editorState} setEditorState={setEditorState} {...formikProps} />;
      }}
    />
  );
}

export default EnhancedCandidateRegularForm;
