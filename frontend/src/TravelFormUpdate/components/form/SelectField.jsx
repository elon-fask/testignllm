import React from 'react';

const SelectField = ({ field, form: { touched, errors }, ...props }) => {
  const isTouched = touched[field.name];
  const hasErrors = !!errors[field.name];
  const errorText = errors[field.name];

  return (
    <div className="field">
      <label className="label">{props.label}</label>
      <div className="control">
        <div className={`select is-fullwidth ${isTouched && (hasErrors ? 'is-danger' : 'is-success')}`}>
          <select {...field}>
            <option value="" disabled>
              Select...
            </option>
            {props.options.map(opt => (
              <option key={opt} value={opt}>
                {opt}
              </option>
            ))}
          </select>
        </div>
      </div>
      {isTouched &&
        hasErrors && (
          <p className="help is-danger animated fadeIn" style={{ backgroundColor: '#fff', padding: '2px' }}>
            {errorText}
          </p>
        )}
    </div>
  );
};

export default SelectField;
