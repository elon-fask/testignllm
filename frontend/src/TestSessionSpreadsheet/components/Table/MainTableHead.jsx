import React from 'react';
import HeaderUpper from './HeaderUpper';
import HeaderContent from './HeaderContent';

const MainTableHead = props => (
  <thead>
    <HeaderUpper view={props.view} />
    <tr className="tableHeader--lower" style={{ height: '110px', maxHeight: '110px', minHeight: '110px' }}>
      {props.visibleColumns.map(col => <HeaderContent key={col} content={col} />)}
    </tr>
  </thead>
);

export default MainTableHead;
