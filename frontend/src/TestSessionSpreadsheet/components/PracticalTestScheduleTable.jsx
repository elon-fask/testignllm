import _groupBy from 'lodash/groupBy';
import React from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { openDialog } from '../actionCreators';
import DayTable from './PracticalTestScheduleTable/DayTable';

const PracticalTestScheduleTable = props => {
  const schedules = _groupBy(props.schedules, 'day');

  return (
    <div style={{ display: 'flex' }}>
      <DayTable schedules={schedules[1]} candidates={props.candidates} openDialog={props.openDialog} day={1} />
      <DayTable schedules={schedules[2]} candidates={props.candidates} openDialog={props.openDialog} day={2} />
      <DayTable schedules={schedules[3]} candidates={props.candidates} openDialog={props.openDialog} day={3} />
    </div>
  );
};

const mapStateToProps = props => {
  return {
    candidates: props.candidates,
    schedules: props.testSession.practicalTestSchedules
  };
};

const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      openDialog
    },
    dispatch
  );

export default connect(mapStateToProps, mapDispatchToProps)(PracticalTestScheduleTable);
