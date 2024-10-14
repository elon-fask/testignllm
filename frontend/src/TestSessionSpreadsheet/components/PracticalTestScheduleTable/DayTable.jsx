import React, { Fragment } from 'react';
import Row from './Row';

const timeslots = [
  '8:00 AM',
  '9:00 AM',
  '10:00 AM',
  '11:00 AM',
  '12:00 PM',
  '1:00 PM',
  '2:00 PM',
  '3:00 PM',
  '4:00 PM',
  '5:00 PM',
  '6:00 PM',
  '7:00 PM',
  '8:00 PM'
];

const DayTable = props => (
  <table className="spreadsheet--practical-test-schedule">
    <thead>
      <tr>
        <th className={`day-${props.day}`}>
          <div>{`Day ${props.day}`}</div>
        </th>
        <th className={`day-${props.day}`}>
          <div>FX or SW</div>
        </th>
        <th className={`day-${props.day}`}>
          <div>Name</div>
        </th>
        <th className={`day-${props.day}`}>
          <div>Practice</div>
        </th>
      </tr>
    </thead>
    <tbody>
      {timeslots.map(timeslot => {
        let fxSchedule = undefined;
        let swSchedule = undefined;

        if (props.schedules) {
          const timeslotSchedules = props.schedules.filter(schedule => schedule.time === timeslot);

          if (timeslotSchedules) {
            fxSchedule = timeslotSchedules.find(schedule => schedule.crane === 'FX');
            swSchedule = timeslotSchedules.find(schedule => schedule.crane === 'SW');
          }
        }

        return (
          <Fragment key={timeslot}>
            <Row
              candidates={props.candidates}
              openDialog={props.openDialog}
              day={props.day}
              timeslot={timeslot}
              crane="FX"
              schedule={fxSchedule}
            />
            <Row
              candidates={props.candidates}
              openDialog={props.openDialog}
              day={props.day}
              timeslot={timeslot}
              crane="SW"
              schedule={swSchedule}
            />
          </Fragment>
        );
      })}
    </tbody>
    <tfoot>
      <tr>
        <td className={`day-${props.day}`}>Placeholder</td>
        <td className={`day-${props.day}`} />
        <td className={`day-${props.day}`} />
        <td className={`day-${props.day}`} />
      </tr>
    </tfoot>
  </table>
);

export default DayTable;
