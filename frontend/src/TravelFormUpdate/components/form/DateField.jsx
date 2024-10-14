import React, { Component } from 'react';

class DateField extends Component {
  componentDidMount() {
    new bulmaCalendar(this.dateFieldUI, {
      dateFormat: 'mm/dd/yyyy',
      overlay: true,
      onSelect: newDate => {
        this.props.form.setFieldValue(this.props.field.name, newDate);
      }
    });
  }

  render() {
    const { field, form: { touched, errors }, ...props } = this.props;
    const isTouched = touched[field.name];
    const hasErrors = !!errors[field.name];
    const errorText = errors[field.name];

    const dateStr =
      field.value && `${field.value.getMonth() + 1}/${field.value.getDate()}/${field.value.getFullYear()}`;

    return (
      <div className="field">
        <label className="label">{props.label}</label>
        <div className="control has-icons-right">
          <input
            ref={dateFieldUI => {
              this.dateFieldUI = dateFieldUI;
            }}
            className={`input ${isTouched && (hasErrors ? 'is-danger' : 'is-success')}`}
            type="text"
            name={field.name}
            value={dateStr}
            onBlur={field.onBlur}
            readOnly
          />
          <span className="icon is-small is-right">
            {isTouched && <i className={`fa ${hasErrors ? 'fa-warning' : 'fa-check'}`} />}
          </span>
        </div>
        {isTouched &&
          hasErrors && (
            <p
              className="help is-danger animated fadeIn"
              style={{ backgroundColor: '#fff', padding: '2px', borderRadius: '3px' }}
            >
              {errorText}
            </p>
          )}
      </div>
    );
  }
}

export default DateField;
