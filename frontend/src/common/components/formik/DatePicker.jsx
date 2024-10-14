import React from 'react';
import DatePickerMUI from 'material-ui/DatePicker';

const DatePicker = ({ field, form: { setFieldValue, touched, errors }, label, ...props }) => {
  return (
    <div>
      <DatePickerMUI
        autoOk
        hintText={props.hintText}
        onChange={(e, value) => {
          setFieldValue(field.name, value, true);
        }}
        textFieldStyle={props.style}
        name={field.name}
        value={field.value}
      />
      {touched[field.name] &&
        errors[field.name] && (
          <div
            style={{
              position: 'relative',
              bottom: '2px',
              fontSize: '12px',
              lineHeight: '12px',
              color: 'rgb(244, 67, 54)',
              transition: 'all 450ms cubic-bezier(0.23, 1, 0.32, 1) 0ms'
            }}
          >
            {errors[field.name]}
          </div>
        )}
    </div>
  );
};

export default DatePicker;
