import React, { Fragment, useState, useEffect } from 'react';
import styled from 'styled-components';
import { Formik, Field } from 'formik';
import RichTextEditor from 'react-rte';
import CheckboxField from '../../../../common/components/formik/bootstrap/CheckboxField';
import EditableTemplate from './EditableTemplate';
import CommonFormElements from './CommonFormElements';
import EmailEditor from './EmailEditor';
import { getSalutationText, getPracticalOnlyText, getSignatureBlockText } from './TextTemplates';

const TextContainer = styled.div`
  display: flex-inline;
  align-items: center;
  line-height: 28px;
`;

function PracticalOnlyCandidateForm(props) {
  const [areDetailsVisible, setAreDetailsVisible] = useState(true);

  if (!props.candidate.hasPracticalTestSchedule) {
    return <div>No practical test schedule!</div>;
  }

  return (
    <CommonFormElements confirmAction={props.handleSubmit} confirmDisabled={!props.candidate.hasPracticalTestSchedule}>
      <div style={{ marginBottom: '24px' }}>
        {props.candidate.hasPracticalTestSchedule ? (
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
        ) : (
          <div style={{ fontStyle: 'italic' }}>
            (Candidate has no practical test schedule. Please schedule a practical test in order to send the
            confirmation email.)
          </div>
        )}
      </div>
    </CommonFormElements>
  );
}

function generateInitialValuesFromProps(props) {
  const {
    candidate: { name, email, testSite, testSchedule, branding, hasPracticalTestSchedule, mergedFormSetup, sender }
  } = props;

  if (!hasPracticalTestSchedule) {
    return {};
  }

  const subject = `Practical Exam Only Confirmation - ${testSite.city} ${testSchedule.day}`;

  const hasSWCab = mergedFormSetup['P_TELESCOPIC_TLL'] === 'on';
  const hasFXCab = mergedFormSetup['P_TELESCOPIC_TSS'] === 'on';

  let craneExam = '';

  if (hasSWCab && hasFXCab) {
    craneExam = 'Fixed-Cab Crane and Swing-Cab Crane';
  } else {
    if (hasSWCab) {
      craneExam = 'Swing-Cab Crane';
    }
    if (hasFXCab) {
      craneExam = 'Fixed-Cab Crane';
    }
  }

  const encodedAddress = encodeURIComponent(
    `${testSite.address}, ${testSite.city}, ${testSite.state}, ${testSite.zip}`
  );
  if(branding != 'ACS'){
const mapUrl = `https://americancraneschool.com/locationsingle?place=Sacramento_California`;
}else{
  const mapUrl = `https://www.google.com/maps/search/?api=1&query=${encodedAddress}`;
}
  return {
    sendEmail: true,
    email,
    subject,
    name,
    craneExam,
    siteName: testSite.name,
    address: testSite.address,
    city: testSite.city,
    state: testSite.state,
    zip: testSite.zip,
    examSchedule: `${testSchedule.day} ${testSchedule.time}`,
    mapUrl,
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
}

function EnhancedPracticalOnlyCandidateForm(props) {
  const initialValues = generateInitialValuesFromProps(props);
  const [editorState, setEditorState] = useState(RichTextEditor.createValueFromString('', 'html'));

  useEffect(() => {
    const editorValue = `${getSalutationText(initialValues)}${getPracticalOnlyText(
      initialValues
    )}${getSignatureBlockText(initialValues)}`;
    const initialState = RichTextEditor.createValueFromString(editorValue, 'html');
    setEditorState(initialState);
  }, []);

  const { sendEmail, email, subject } = initialValues;

  return (
    <Formik
      initialValues={{ sendEmail, email, subject }}
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
        return (
          <PracticalOnlyCandidateForm
            {...props}
            {...formikProps}
            editorState={editorState}
            setEditorState={setEditorState}
          />
        );
      }}
    />
  );
}

export default EnhancedPracticalOnlyCandidateForm;
