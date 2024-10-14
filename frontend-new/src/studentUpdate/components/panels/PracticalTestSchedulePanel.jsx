import React, { Component } from 'react';
import { sortPracticalTestSchedule, parsePracticeHours } from '../../../common/practicalTestSchedule';

class PracticalTestSchedulePanel extends Component {
  handleDeleteScheduleClick = e => {
    const id = parseInt(e.currentTarget.dataset.id, 10);
    this.props.openDialog('DELETE_PRACTICAL_SCHEDULE', { id });
  };

  render() {
    const { props } = this;
    const schedule = sortPracticalTestSchedule(props.schedule);

    return (
      <div className="panel panel-default">
        <div className="panel-heading">
          <h4>Practical Test Schedule</h4>
        </div>
        <div className="panel-body">
          <div style={{ marginBottom: '20px' }}>
            <div>{`Unused Paid Practice Time: ${props.candidate.practiceTimeCredits}`}</div>
            <button
              className="btn btn-primary"
              type="button"
              data-toggle="modal"
              data-target="#modal"
              onClick={() => {
                props.openDialog('SET_PRACTICE_TIME_CREDITS');
              }}
            >
              Set Practice Time Credits
            </button>
          </div>
          {schedule.length > 0 && (
            <table className="table table-striped">
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>New or Retest</th>
                  <th>Practice</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {schedule.map(schedule => (
                  <tr key={schedule.id}>
                    <td>{schedule.day}</td>
                    <td>{schedule.date}</td>
                    <td>{schedule.time}</td>
                    <td style={{ textTransform: 'capitalize' }}>{schedule.new_or_retest.toLowerCase()}</td>
                    <td>{parsePracticeHours(schedule.practice_hours)}</td>
                    <td>
                      <button
                        type="button"
                        data-toggle="modal"
                        data-target="#modal"
                        data-id={schedule.id}
                        className="btn btn-danger"
                        onClick={this.handleDeleteScheduleClick}
                      >
                        <i className="fa fa-trash" aria-hidden="true" />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
          <a
            href={
              props.practicalTestSessionId
                ? `/admin/testsession/spreadsheet?id=${props.practicalTestSessionId}&view=PRACTICAL_TEST_SCHEDULE`
                : '#'
            }
            className="btn btn-primary"
            disabled={!props.practicalTestSessionId}
          >
            Practical Test Schedule Spreadsheet
          </a>
        </div>
      </div>
    );
  }
}

export default PracticalTestSchedulePanel;
