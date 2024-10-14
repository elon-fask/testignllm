import axios from 'axios';
import * as React from 'react';
import { v1 as uuidv1 } from 'uuid';
import { formatMoney } from 'accounting';
import ExportBtn from './ExportBtn';

const moneyValues = ['customerCharges', 'totalPayment', 'totalAmountOwed'];

function Results(props: any) {
  const handleExportToExcel = async () => {
    try {
      const { columnHeadings, rowValues } = props.results;

      const payload = {
        data: [
          columnHeadings,
          ...rowValues.map(row => {
            return [row.rowStart, ...row.columnValues];
          })
        ],
        filename: 'Custom Report.xlsx',
        styles: [],
        wsName: 'Custom Report'
      };

      const {
        data: { link }
      } = await axios.post('/admin/testsession/render-spreadsheet', payload);
      window.location.href = link;
    } catch (e) {
      console.error(e);
    }
  };

  return (
    <div className="container">
      <table className="table is-bordered is-striped is-hoverable">
        <thead>
          <tr>
            {props.results.columnHeadings.map((heading: any) => (
              <th key={uuidv1()}>{heading}</th>
            ))}
          </tr>
        </thead>
        <tbody>
          {props.results.rowValues.map((rowValue: any) => {
            return (
              <tr key={uuidv1()}>
                <td>{rowValue.rowStart}</td>
                {rowValue.columnValues.map((columnValue: any, i) => {
                  if (moneyValues.includes(props.query.columns[i].value)) {
                    return <td key={uuidv1()}>{formatMoney(columnValue)}</td>;
                  }

                  return <td key={uuidv1()}>{columnValue}</td>;
                })}
              </tr>
            );
          })}
        </tbody>
      </table>
      <ExportBtn handleClick={handleExportToExcel} />
    </div>
  );
}

export default Results;
