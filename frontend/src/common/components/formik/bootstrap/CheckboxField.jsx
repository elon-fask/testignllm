import React from 'react';

const CheckboxField = props => (
  <div className="checkbox">
    <label>
      <input type="checkbox" value="" {...props.field} />
      {props.label}
    </label>
  </div>
);

export default CheckboxField;
