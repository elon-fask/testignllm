import { format } from 'date-fns';
import { intToExcelCol, excelColToInt, convertDateExcel } from './excel';

function getAdjacentValueFromRowName(worksheetData, rowTitleMapping) {
  const result = {};

  Object.keys(worksheetData).forEach(rowKey => {
    const row = worksheetData[rowKey];

    Object.keys(row).forEach(colKey => {
      const val = row[colKey];

      Object.keys(rowTitleMapping).forEach(rowTitleKey => {
        const rowTitle = rowTitleMapping[rowTitleKey];

        if (val === rowTitle) {
          const adjacentColKey = intToExcelCol(excelColToInt(colKey) + 1);
          result[rowTitleKey] = row[adjacentColKey];
        }
      });
    });
  });

  return result;
}

export function readTestSessionInfoFromWorksheetData(worksheetData) {
  const rowTitleMapping = {
    testSiteName: 'Test Site Name:',
    testSiteCoordinator: 'Test Site Coordinator:',
    testSiteAddress: 'Test Site Address:',
    testSiteNumber: 'Test Site Number:',
    testDate: 'Test Date:',
    instructor: 'Instructor:',
    practicalExaminer: 'Practical Examiner:',
    proctor: 'Proctor:',
    practicalTestSiteCode: 'Practical Test Site Code:'
  };

  const result: any = getAdjacentValueFromRowName(worksheetData, rowTitleMapping);

  if (result.testDate) {
    result.testDate = format(convertDateExcel(result.testDate), 'YYYY-MM-DD');
  }

  return result;
}

export function readRegularCandidateRosterFromWorksheetData(worksheetData) {
  let startRow = 0;
  let endRow = 0;

  Object.keys(worksheetData).forEach(rowKey => {
    const row = worksheetData[rowKey];

    if (row.A === 'Name' && row.B === 'Company' && row.C === 'Type') {
      startRow = parseInt(rowKey, 10);
    }

    if (row.A === 'Total Candidates') {
      endRow = parseInt(rowKey, 10);
    }
  });

  const candidates = [];

  for (let i = startRow + 1; i < endRow; i++) {
    const row = worksheetData[i];
    const nameArr = row.A.split(', ');

    candidates.push({
      lastName: nameArr[0],
      firstName: nameArr[1],
      company: row.B,
      type: row.C,
      charged: row.P,
      paid: row.Q,
      phone: row.AE,
      cell: row.AF,
      fax: row.AG,
      companyPhone: row.AH,
      email: row.AI,
      contactName: row.AJ,
      notes: row.AK
    });
  }

  return candidates;
}
