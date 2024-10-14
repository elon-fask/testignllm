import React from 'react';
import HeaderContent from './HeaderContent';

const FixedTableHead = props => (
  <thead>
    <tr className="tableHeader--upper">
      <td colSpan={2}>{props.title}</td>
    </tr>
    <tr className="tableHeader--lower" style={{ height: '110px', maxHeight: '110px', minHeight: '110px' }}>
      {props.visibleColumns.map(col => <HeaderContent key={col} content={col} />)}
    </tr>
  </thead>
);

export default FixedTableHead;
