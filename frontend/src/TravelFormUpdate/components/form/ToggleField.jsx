import React from 'react';

const ToggleField = ({ field, label }) => (
  <div className="field">
    <input id={field.name} type="checkbox" className="switch is-rounded is-info" checked={field.value} {...field} />
    <label htmlFor={field.name}>{label}</label>
  </div>
);

export default ToggleField;
