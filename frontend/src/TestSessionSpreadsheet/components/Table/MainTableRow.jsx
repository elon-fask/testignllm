import React from 'react';
import CellContent from './CellContent';
import { viewTypes } from '../../reducers/ui';

const practicalTestScheduleDayStyleMapping = {
  1: '#b3a2c7',
  2: '#93cddd',
  3: '#c3d69b',
  4: '#ffff00',
  5: '#ffc0cb',
};

const getCraneVal = (numCranes, showCount) => {
  if (showCount) {
    return numCranes > 0 ? numCranes : '';
  }
  return numCranes > 0 ? 'x' : '';
};

const MainTableRow = props => {
  const commonProps = {
    table: props.table,
    row: props.row,
    candidateId: props.candidate.id,
    candidateID: props.candidate.id
  };

  const { grades, numCranesSW, numCranesFX, testSchedule } = props.candidate;
  const { countPracticalCranes } = props.viewOptions;
  let numCranesSWVal;
  let numCranesFXVal;
  if(props.view !== 'PRACTICAL_TEST_SCHEDULE' || !testSchedule.practice_time_only) {
    numCranesSWVal = getCraneVal(numCranesSW, countPracticalCranes);
    numCranesFXVal = getCraneVal(numCranesFX, countPracticalCranes);
  }

  const style = {
    height: props.rowHeight
  };

  if (props.view === viewTypes.PRACTICAL_TEST_SCHEDULE) {
    const { day } = props.candidate.testSchedule;
    if(!day) return null;
    style.backgroundColor = practicalTestScheduleDayStyleMapping[day];
  }
  return (
    <tr style={style}>
      {props.visibleColumns.map(col => {
    return   (
        <CellContent
          {...props}
          key={col}
          content={col}
          commonProps={commonProps}
          grades={grades}
          numCranesSWVal={numCranesSWVal}
          numCranesFXVal={numCranesFXVal}
        />
      )})}
    </tr>
  );
};

export default MainTableRow;
