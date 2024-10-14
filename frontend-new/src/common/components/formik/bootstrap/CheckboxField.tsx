import React from 'react';
import { FieldProps } from 'formik';

interface Props extends FieldProps {
  label: string;
}

const CheckboxField = (props: Props) => {
  return (
    <div className="checkbox">
      <label>
        <input type="checkbox" {...props.field} checked={props.field.value} />
        {props.label}
      </label>
    </div>
  );
};

export default CheckboxField;
