import React from 'react';
import FixedTableRow from './FixedTableRow';
import TotalsRow from './TotalsRow';

const FixedTableBody = props => (
  <tbody>
    {props.candidateIDs.map((candidateID, index) => {
      const candidate = props.candidates[candidateID];
      const applicationType = props.applicationTypes[candidate.applicationTypeID];

      return (
        <FixedTableRow
          view={props.originalView}
          viewOptions={props.viewOptions}
          visibleColumns={props.visibleColumns}
          key={candidateID}
          row={index}
          rowHeight={props.rowHeight}
          candidate={candidate}
          applicationType={applicationType}
          table={props.table}
        />
      );
    })}
    <TotalsRow {...props} />
  </tbody>
);

export default FixedTableBody;
