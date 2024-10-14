import React from 'react';
import SelectFieldMUI from 'material-ui/SelectField';
import MenuItem from 'material-ui/MenuItem';

const SelectField = ({ field, form: { setFieldValue, touched, errors }, label, ...props }) => {
  return (
    <SelectFieldMUI
      style={props.style}
      floatingLabelText={label}
      floatingLabelFixed
      value={field.value}
      onChange={(e, k, value) => {
        setFieldValue(field.name, value, true);
      }}
      errorText={touched[field.name] && errors[field.name]}
    >
      {props.nullable && <MenuItem value={null} primaryText="" />}
      {Object.keys(props.options).map(optKey => (
        <MenuItem key={optKey} value={optKey} primaryText={props.options[optKey]} />
      ))}
    </SelectFieldMUI>
  );
};

export default SelectField;
