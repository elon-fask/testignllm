import React from 'react';

const SelectField = ({ field, form: { setFieldValue, touched, errors }, label, ...props }) => {
  const isTouched = touched[field.name];
  const hasErrors = isTouched && !!errors[field.name];
  const errorText = errors[field.name];
  const hasSuccess = isTouched && !hasErrors;

  return (
    <div className={`form-group ${hasSuccess ? 'has-success' : ''} ${hasErrors ? 'has-error' : ''}`}>
      <label className="control-label" htmlFor={props.id}>
        {label}
      </label>
      <select className="form-control" id={props.id} name={field.name} {...field}>
        <option value="" disabled />
        {Object.keys(props.options).map(optKey => (
          <option key={optKey} value={optKey}>
            {props.options[optKey]}
          </option>
        ))}
      </select>
      {hasErrors && <div className="help-block">{errorText}</div>}
    </div>
  );
};

export default SelectField;
