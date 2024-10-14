import * as React from 'react';
import { FieldProps } from 'formik';

interface Option {
  value: string | number;
  label: string;
}

interface RadioGroupProps extends FieldProps {
  options: Option[];
}

function RadioGroup({ field, form: { touched, errors }, ...props }: RadioGroupProps) {
  const isTouched = touched[field.name];
  const hasErrors = !!errors[field.name];
  const errorText = errors[field.name];

  return (
    <div className="control">
      {props.options.map(({ value, label }) => {
        return (
          <label key={value} className="radio">
            <input
              type="radio"
              {...field}
              name={field.name}
              value={value}
              checked={field.value === value}
              style={{ marginRight: '0.2rem' }}
            />
            {label}
          </label>
        );
      })}
      {isTouched && hasErrors && (
        <p className="help is-danger animated fadeIn" style={{ backgroundColor: '#fff', padding: '2px' }}>
          {errorText}
        </p>
      )}
    </div>
  );
}

export default RadioGroup;
