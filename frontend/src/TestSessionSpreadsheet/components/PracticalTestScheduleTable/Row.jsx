import React, { Component } from 'react';
import { dialogTypes } from '../../reducers/ui';
import Cell from './Cell';

class Row extends Component {
  handleClick = () => {
    const { day, timeslot, crane, openDialog } = this.props;

    openDialog(dialogTypes.PRACTICAL_TEST_SCHEDULE, { day, timeslot, crane });
  };

  render() {
    const { props } = this;

    const style = {};

    if (props.schedule) {
      let nameValue = '';
      let practiceValue = '';

      if (props.schedule.type === 'MAINTENANCE') {
        style.backgroundColor = '#D9D9D9';
        nameValue = 'Yard Maintenance';
      } else {
        nameValue = props.candidates[props.schedule.candidate_id].name;
        if (props.schedule.type === 'PRACTICE') {
          practiceValue = 'Yes';
        } else {
          practiceValue = 'No';
        }
      }

      return (
        <tr style={style}>
          <Cell value={props.timeslot} onClick={this.handleClick} />
          <Cell value={props.crane} onClick={this.handleClick} />
          <Cell value={nameValue} onClick={this.handleClick} />
          <Cell value={practiceValue} onClick={this.handleClick} />
        </tr>
      );
    }

    return (
      <tr>
        <Cell value={props.timeslot} onClick={this.handleClick} />
        <Cell value={props.crane} onClick={this.handleClick} />
        <Cell value="" onClick={this.handleClick} />
        <Cell value="" onClick={this.handleClick} />
      </tr>
    );
  }
}

export default Row;
