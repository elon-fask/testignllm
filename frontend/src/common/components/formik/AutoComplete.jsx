import React from 'react';
import AutoCompleteMUI from 'material-ui/AutoComplete';

const AutoComplete = ({ field, form: { setFieldValue, touched, errors }, label, ...props }) => {
  const isTouched = touched[field.name];
  const hasErrors = !!errors[field.name];

  return (
    <AutoCompleteMUI
      name={field.name}
      value={field.value}
      searchText={field.value}
      onUpdateInput={value => {
        setFieldValue(field.name, value, true);
      }}
      dataSource={props.dataSource}
      floatingLabelText={label}
      floatingLabelFixed
      errorText={isTouched && hasErrors && errors[field.name]}
      rows={props.rows}
      style={props.style}
      {...props.options}
    />
  );
};

export default AutoComplete;
