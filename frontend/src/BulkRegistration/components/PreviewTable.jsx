import _range from 'lodash/range';
import React from 'react';
import PropTypes from 'prop-types';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';

const PreviewTable = ({ highestColumn, table }) => {
  const columnList = _range('A'.charCodeAt(0), highestColumn.charCodeAt(0) + 1).map(columnCharCode =>
    String.fromCharCode(columnCharCode)
  );
  /* eslint-disable react/no-array-index-key */
  return (
    <Table selectable={false}>
      <TableHeader displaySelectAll={false} adjustForCheckbox={false}>
        <TableRow>
          <TableHeaderColumn />
          {columnList.map(column => <TableHeaderColumn key={column}>{`Column ${column}`}</TableHeaderColumn>)}
        </TableRow>
      </TableHeader>
      <TableBody displayRowCheckbox={false} stripedRows>
        {table.map((row, i) => (
          <TableRow key={i}>
            <TableRowColumn>{`Row ${i + 1}`}</TableRowColumn>
            {row.map(({ value }, j) => (
              <TableRowColumn
                key={j}
                style={{
                  whiteSpace: 'normal',
                  wordWrap: 'break-word'
                }}
              >
                {value}
              </TableRowColumn>
            ))}
          </TableRow>
        ))}
      </TableBody>
    </Table>
  );
  /* eslint-disable react/no-array-index-key */
};

PreviewTable.propTypes = {
  highestColumn: PropTypes.string.isRequired,
  table: PropTypes.arrayOf(PropTypes.arrayOf(PropTypes.objectOf(() => true))).isRequired
};

export default PreviewTable;
