import React from 'react';

const FieldWrapper = ({ label, labelStyle, style, children }) => (
  <div style={{ display: 'flex', alignItems: 'center', ...style }}>
    <span
      style={{
        fontWeight: 'bold',
        marginRight: '10px',
        width: '160px',
        textAlign: 'right',
        ...labelStyle
      }}
    >
      {label}
    </span>
    {children}
  </div>
);

export default FieldWrapper;
