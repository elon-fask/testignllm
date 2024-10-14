import React from 'react';

const OrderedSelectField = ({ field, form: { touched, errors }, label, ...props }) => {
  const isTouched = touched[field.name];
  const hasErrors = isTouched && !!errors[field.name];
  const errorText = errors[field.name];
  const hasSuccess = isTouched && !hasErrors;

  return (
    <div
      className={`form-group ${hasSuccess ? 'has-success' : ''} ${hasErrors ? 'has-error' : ''}`}
      style={props.style}
    >
      <label className="control-label" htmlFor={props.id} style={{ fontWeight: 'bold', ...props.labelStyle }}>
        {label}
        <select className="form-control" id={props.id} name={field.name} {...field}>
          <option value="" disabled={typeof props.disableBlank === 'undefined' ? true : props.disableBlank} />
          {props.options.map(({ key, value, text }) => (
            <option key={key} value={value}>
              {text}
            </option>
          ))}
        </select>
      </label>
      {hasErrors && <div className="help-block">{errorText}</div>}
    </div>
  );
};

export default OrderedSelectField;
