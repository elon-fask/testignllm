import React from 'react';
import SelectFieldMUI from 'material-ui/SelectField';
import MenuItem from 'material-ui/MenuItem';

const OrderedSelectField = ({ field, form: { setFieldValue, touched, errors }, label, ...props }) => {
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
      {props.options.map(({ key, value, text }) => <MenuItem key={key} value={value} primaryText={text} />)}
    </SelectFieldMUI>
  );
};

export default OrderedSelectField;
