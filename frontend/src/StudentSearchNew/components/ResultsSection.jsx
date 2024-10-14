import React from 'react';
import RaisedButton from 'material-ui/RaisedButton';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import { testNames, gradeValues, gradeColors } from '../../common/grades';

const ResultsSection = props => (
  <div style={{ marginTop: '40px' }}>
    <div style={{ display: 'flex' }}>
      <h4 style={{ fontWeight: 'bold', marginRight: '20px' }}>Results</h4>
      {props.results.length > 0 && (
        <RaisedButton label="Download as Excel File" primary onClick={props.handleDownloadResults} />
      )}
    </div>
    <div>
      <Table selectable={false}>
        <TableHeader adjustForCheckbox={false} displaySelectAll={false} enableSelectAll={false}>
          <TableRow>
            <TableHeaderColumn>Name</TableHeaderColumn>
            <TableHeaderColumn>Company</TableHeaderColumn>
            <TableHeaderColumn>Email</TableHeaderColumn>
            <TableHeaderColumn>Grades</TableHeaderColumn>
          </TableRow>
        </TableHeader>
        <TableBody displayRowCheckbox={false}>
          {props.results.map(candidate => {
            return (
              <TableRow key={candidate.id}>
                <TableRowColumn>
                  <a href={`/admin/candidates/update?id=${candidate.idHash}`}>{candidate.name}</a>
                </TableRowColumn>
                <TableRowColumn>{candidate.company}</TableRowColumn>
                <TableRowColumn>{candidate.email}</TableRowColumn>
                <TableRowColumn>
                  {candidate.grades && Object.keys(candidate.grades).length > 0
                    ? Object.keys(candidate.grades).map(grade => {
                        const gradeLabel = testNames[grade];
                        const gradeValue = gradeValues[candidate.grades[grade]];
                        const gradeColor = gradeColors[candidate.grades[grade]];

                        return (
                          <div
                            key={grade}
                            style={{ fontWeight: 'bold', color: gradeColor }}
                          >{`${gradeLabel}: ${gradeValue}`}</div>
                        );
                      })
                    : 'No grades available'}
                </TableRowColumn>
              </TableRow>
            );
          })}
        </TableBody>
      </Table>
    </div>
  </div>
);

export default ResultsSection;
