import React from 'react';

const TextAreaField = ({ field, form: { touched, errors }, ...props }) => {
  const isTouched = touched[field.name];
  const hasErrors = !!errors[field.name];
  const errorText = errors[field.name];

  return (
    <div className="field">
      <label className="label">{props.label}</label>
      <div className="control">
        <textarea
          rows={props.rows || 2}
          className={`textarea ${isTouched && (hasErrors ? 'is-danger' : 'is-success')}`}
          {...field}
        />
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

export default TextAreaField;
