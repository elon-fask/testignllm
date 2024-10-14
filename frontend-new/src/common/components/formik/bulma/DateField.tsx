import * as React from 'react';
import { FieldProps } from 'formik';
import { isValid as isValidDate, format as formatDate } from 'date-fns';
import styled from 'styled-components';
import bulmaCalendar from 'bulma-calendar';
import 'bulma-calendar/dist/css/bulma-calendar.min.css';

const DateDiv = styled.div`
  & .datepicker .datepicker-nav {
    background-color: #0471af;
  }

  & .button.is-text:active {
    background-color: #0471af;
  }

  & .bulma-datepicker .bulma-datepicker-header .bulma-datepicker-selection-start .bulma-datepicker-selection-day {
    color: #004990;
  }

  & .bulma-datepicker .datepicker-body .datepicker-dates .datepicker-days .datepicker-date .date-item.is-today {
    border-color: #0471af;
    color: #004990;
  }

  & .bulma-datepicker .datepicker-body .datepicker-dates .datepicker-days .datepicker-date .date-item:hover {
    border-color: #0471af;
  }

  & .bulma-datepicker .datepicker-body .datepicker-dates .datepicker-days .datepicker-date .date-item.is-active {
    background-color: #0471af;
    border-color: #0471af;
  }

  & .bulma-datepicker .datepicker-body .datepicker-months .datepicker-month.is-active,
  .bulma-datepicker .datepicker-body .datepicker-years .datepicker-year.is-active {
    background-color: #0471af;
    border-color: #0471af;
  }

  & .bulma-datepicker .datepicker-body .datepicker-months .datepicker-month:hover,
  .bulma-datepicker .datepicker-body .datepicker-years .datepicker-year:hover {
    border-color: #0471af;
  }
`;

interface DateFieldProps extends FieldProps {
  label: string;
}

function DateField(props: DateFieldProps) {
  const dateFieldEl = React.useRef<HTMLInputElement>(null);
  const calendar = React.useRef(null);

  React.useEffect(() => {
    calendar.current = bulmaCalendar.attach(dateFieldEl.current);
    calendar.current.datePicker.on('select', (e: any) => {
      props.form.setFieldValue(props.field.name, e.data.date.start);
    });
    return () => {
      calendar.current.destroy();
    };
  }, []);

  const {
    field,
    form: { touched, errors }
  } = props;
  const isTouched = touched[field.name];
  const hasErrors = !!errors[field.name];
  const errorText = errors[field.name];

  let value = '';

  try {
    value = isValidDate(field.value) && formatDate(field.value, 'M-D-YYYY');
  } catch (e) {
    value = '';
  }

  return (
    <DateDiv className="field">
      <label className="label">{props.label}</label>
      <div className="control has-icons-right">
        <input
          ref={dateFieldEl}
          className={`input ${isTouched && (hasErrors ? 'is-danger' : 'is-success')}`}
          type="text"
          value={value}
          name={field.name}
          onBlur={field.onBlur}
          readOnly={true}
        />
        <span className="icon is-small is-right">
          {isTouched && <i className={`fa ${hasErrors ? 'fa-warning' : 'fa-check'}`} />}
        </span>
      </div>
      {isTouched && hasErrors && (
        <p
          className="help is-danger animated fadeIn"
          style={{ backgroundColor: '#fff', padding: '2px', borderRadius: '3px' }}
        >
          {errorText}
        </p>
      )}
    </DateDiv>
  );
}

export default DateField;
