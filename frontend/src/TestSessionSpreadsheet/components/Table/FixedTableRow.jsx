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

const FixedTableRow = props => {
  const commonProps = {
    table: props.table,
    row: props.row,
    candidateId: props.candidate.id,
    candidateID: props.candidate.id
  };

  const { grades, numCranesSW, numCranesFX } = props.candidate;
  const { countPracticalCranes } = props.viewOptions;

  /* eslint-disable no-nested-ternary */
  const numCranesSWVal = countPracticalCranes ? numCranesSW || '' : numCranesSW > 0 ? 'x' : '';
  const numCranesFXVal = countPracticalCranes ? numCranesFX || '' : numCranesSW > 0 ? 'x' : '';
  /* eslint-enable no-nested-ternary */

  const style = { height: props.rowHeight };

  if (props.view === viewTypes.PRACTICAL_TEST_SCHEDULE) {
    const { day } = props.candidate.testSchedule;
    if(!day) return null;
    style.backgroundColor = practicalTestScheduleDayStyleMapping[day];
  }

  return (
    <tr style={style}>
      {props.visibleColumns.map(col => (
        <CellContent
          key={col}
          content={col}
          commonProps={commonProps}
          grades={grades}
          numCranesSWVal={numCranesSWVal}
          numCranesFXVal={numCranesFXVal}
          {...props}
        />
      ))}
    </tr>
  );
};

export default FixedTableRow;
