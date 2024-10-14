import React from 'react';
import { FieldProps } from 'formik';

interface Props extends FieldProps {
  label: string;
  id: string;
  options: {
    [key: string]: string;
  };
}

const SelectField = ({ field, form: { touched, errors }, label, ...props }: Props) => {
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
        <option value="" disabled={true} />
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
