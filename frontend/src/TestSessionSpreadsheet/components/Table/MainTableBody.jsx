import React from 'react';
import MainTableRow from './MainTableRow';
import TotalsRow from './TotalsRow';

const MainTableBody = props => (
  <tbody>
    {props.candidateIDs.map((candidateID, index) => {
      const candidate = props.candidates[candidateID];
      const applicationType = props.applicationTypes[candidate.applicationTypeID];

      return (
        <MainTableRow
          view={props.view}
          viewOptions={props.viewOptions}
          visibleColumns={props.visibleColumns}
          key={candidateID}
          row={index}
          rowHeight={props.rowHeight}
          setRowHeight={props.setRowHeight}
          candidate={candidate}
          applicationType={applicationType}
          table={props.table}
        />
      );
    })}
    <TotalsRow {...props} />
  </tbody>
);

export default MainTableBody;
