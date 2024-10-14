import React from 'react';
import CheckboxMUI from 'material-ui/Checkbox';

const Checkbox = ({ field, form: { setFieldValue }, label, labelStyle, style }) => (
  <CheckboxMUI
    label={label}
    labelStyle={labelStyle}
    style={style}
    checked={field.value}
    name={field.name}
    onCheck={(e, value) => {
      setFieldValue(field.name, value);
    }}
  />
);

export default Checkbox;
