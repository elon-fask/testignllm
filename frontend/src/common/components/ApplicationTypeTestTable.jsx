import React from 'react';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import BaseCheck from 'material-ui/svg-icons/action/done';
import BaseCross from 'material-ui/svg-icons/content/clear';
import { red500, green500 } from 'material-ui/styles/colors';

const tableCellStyle = {
  border: 'none',
  textAlign: 'center'
};

const Check = <BaseCheck color={green500} />;
const Cross = <BaseCross color={red500} />;

const ApplicationTypeTestTable = ({ formSetup }) => {
  return (
    <Table selectable={false}>
      <TableHeader adjustForCheckbox={false} displaySelectAll={false}>
        <TableRow>
          <TableHeaderColumn style={tableCellStyle}>Core</TableHeaderColumn>
          <TableHeaderColumn style={tableCellStyle}>Written SW</TableHeaderColumn>
          <TableHeaderColumn style={tableCellStyle}>Written FX</TableHeaderColumn>
          <TableHeaderColumn style={tableCellStyle}>Practical SW</TableHeaderColumn>
          <TableHeaderColumn style={tableCellStyle}>Practical FX</TableHeaderColumn>
        </TableRow>
      </TableHeader>
      <TableBody displayRowCheckbox={false}>
        <TableRow>
          <TableRowColumn style={tableCellStyle}>{formSetup.coreEnabled ? Check : Cross}</TableRowColumn>
          <TableRowColumn style={tableCellStyle}>{formSetup.writtenSWEnabled ? Check : Cross}</TableRowColumn>
          <TableRowColumn style={tableCellStyle}>{formSetup.writtenFXEnabled ? Check : Cross}</TableRowColumn>
          <TableRowColumn style={tableCellStyle}>{formSetup.practicalSWEnabled ? Check : Cross}</TableRowColumn>
          <TableRowColumn style={tableCellStyle}>{formSetup.practicalFXEnabled ? Check : Cross}</TableRowColumn>
        </TableRow>
      </TableBody>
    </Table>
  );
};

export default ApplicationTypeTestTable;
