import React, { Component } from 'react';

class DateTimePickerField extends Component {
  componentDidMount() {
    const datePicker = $(`#${this.props.field.name}-date-time-picker`);
    datePicker.datetimepicker(this.props.options);

    datePicker.on('dp.change', ({ date }) => {
      this.props.form.setFieldValue(this.props.field.name, date.format(this.props.options.format));
    });
  }

  render() {
    const { field, form: { touched, errors } } = this.props;
    const isTouched = touched[field.name];
    const hasErrors = isTouched && !!errors[field.name];
    const errorText = errors[field.name];
    const hasSuccess = isTouched && !hasErrors;

    const id = `${field.name}-date-time-picker`;

    return (
      <div
        style={{ position: 'relative', ...this.props.style }}
        className={`form-group ${hasSuccess ? 'has-success' : ''} ${hasErrors ? 'has-error' : ''}`}
      >
        <label htmlFor={id}>
          {this.props.label}
          <input
            type="text"
            className="form-control"
            id={id}
            autoComplete={this.props.autoComplete || 'off'}
            {...field}
          />
        </label>
        {hasErrors && (
          <div className="help-block" style={{ position: 'absolute' }}>
            {errorText}
          </div>
        )}
      </div>
    );
  }
}

export default DateTimePickerField;
