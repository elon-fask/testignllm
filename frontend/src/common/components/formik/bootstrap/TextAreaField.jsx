import React from 'react';

const TextAreaField = ({ field, form: { setFieldValue, touched, errors }, label, ...props }) => {
  const isTouched = touched[field.name];
  const hasErrors = isTouched && !!errors[field.name];
  const errorText = errors[field.name];
  const hasSuccess = isTouched && !hasErrors;

  return (
    <div className={`form-group ${hasSuccess ? 'has-success' : ''} ${hasErrors ? 'has-error' : ''}`}>
      <label className="control-label" htmlFor={props.id}>
        {label}
      </label>
      <textarea id={props.id} className="form-control" name="CandidateTransactions[remarks]" {...field} />
      {hasErrors && <div className="help-block">{errorText}</div>}
    </div>
  );
};

export default TextAreaField;
