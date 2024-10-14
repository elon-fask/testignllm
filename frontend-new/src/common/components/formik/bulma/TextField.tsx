import * as React from 'react';
import { FieldProps } from 'formik';

interface TextFieldProps extends FieldProps {
  className?: string;
  label: string;
}

function TextField({ field, form: { touched, errors }, ...props }: TextFieldProps) {
  const isTouched = touched[field.name];
  const hasErrors = !!errors[field.name];
  const errorText = errors[field.name];

  return (
    <div className={`field ${props.className || ''}`}>
      <label className="label">{props.label}</label>
      <div className="control has-icons-right">
        <input className={`input ${isTouched && (hasErrors ? 'is-danger' : 'is-success')}`} type="text" {...field} />
        <span className="icon is-small is-right">
          {isTouched && <i className={`fa ${hasErrors ? 'fa-warning' : 'fa-check'}`} />}
        </span>
      </div>
      {isTouched && hasErrors && (
        <p className="help is-danger animated fadeIn" style={{ backgroundColor: '#fff', padding: '2px' }}>
          {errorText}
        </p>
      )}
    </div>
  );
}

export default TextField;
