import React from 'react';
import TextFieldMUI from 'material-ui/TextField';

const TextField = ({ field, form: { setFieldValue, touched, errors }, label, ...props }) => {
  const isTouched = touched[field.name];
  const hasErrors = !!errors[field.name];

  return (
    <TextFieldMUI
      name={field.name}
      value={field.value}
      onChange={(e, value) => {
        setFieldValue(field.name, value, true);
      }}
      type={props.type || 'text'}
      floatingLabelText={label}
      floatingLabelFixed
      errorText={isTouched && hasErrors && errors[field.name]}
      {...props.options}
      multiLine={props.multiline}
      rows={props.rows}
      style={props.style}
    />
  );
};

export default TextField;
