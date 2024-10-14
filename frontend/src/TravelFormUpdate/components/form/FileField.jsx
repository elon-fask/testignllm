import React from 'react';

const FileField = ({ field, form: { touched, errors, setFieldValue }, ...props }) => {
  return (
    <div className="file has-name">
      <label className="file-label">
        <input
          id={props.id}
          className="file-input"
          type="file"
          name={field.name}
          onChange={e => {
            setFieldValue(field.name, e.target.files[0].name);
          }}
        />
        <span className="file-cta">
          <span className="file-icon">
            <i className="fa fa-upload" />
          </span>
          <span className="file-label">Upload File</span>
        </span>
        {field.value && <span className="file-name">{field.value}</span>}
      </label>
    </div>
  );
};

export default FileField;
