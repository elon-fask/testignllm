import React, { CSSProperties } from 'react';
import { FieldProps } from 'formik';

interface Props extends FieldProps {
  label: string;
  id: string;
  list?: string;
  style?: CSSProperties;
  type?: string;
}

const TextField = ({ field, form: { touched, errors }, label, ...props }: Props) => {
  const isTouched = touched[field.name];
  const hasErrors = isTouched && !!errors[field.name];
  const errorText = errors[field.name];
  const hasSuccess = isTouched && !hasErrors;

  const dataListProps = props.list
    ? {
        list: props.list
      }
    : {};

  return (
    <div
      className={`form-group ${hasSuccess ? 'has-success' : ''} ${hasErrors ? 'has-error' : ''}`}
      style={props.style}
    >
      <label className="control-label" htmlFor={props.id}>
        {label}
      </label>
      <input
        type={props.type || 'text'}
        id={props.id}
        className="form-control"
        name={field.name}
        {...field}
        {...dataListProps}
      />
      {hasErrors && <div className="help-block">{errorText}</div>}
    </div>
  );
};

export default TextField;
