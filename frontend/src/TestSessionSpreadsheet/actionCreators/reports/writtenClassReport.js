import axios from 'axios';
import { prepareMainTableData, getWrittenCandidateState } from '../../lib/helpers';
import { getPaymentStatusConditionalStyle } from './styles/candidateRowConditionalStyles';

export const prepareWrittenClassReportData = rawState => {
  const { testSession } = rawState;

  const [regularCandidateIDs, regularCandidates] = getWrittenCandidateState(rawState);

  const { applicationTypes, candidateIDs, candidates, ...state } = prepareMainTableData({
    ...rawState,
    candidateIDs: regularCandidateIDs,
    candidates: regularCandidates
  });

  const testSessionRows = [
    ['', '', '', '', '', '', 'Class Roster'],
    [],
    ['Test Site Name', testSession.testSiteName, '', '', '', '', '', '', '', '', 'Instructor', testSession.instructor],
    [
      'Test Site Coordinator',
      testSession.testSiteCoordinator,
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      'Practical Examiner',
      testSession.practicalExaminer
    ],
    [
      'Test Site Address',
      testSession.testSiteAddress,
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      'Practical TS #',
      testSession.practicalTestSiteCode
    ],
    ['Test Date', testSession.testingDate],
    ['Test Site Number', testSession.testSiteNumber]
  ];

  const classTopRows = [
    ['', '', '', 'Written', '', '', 'Practical'],
    [
      'Name',
      'Company',
      'Type',
      'Core',
      'SW',
      'FX',
      'SW Cab',
      'FX Cab',
      'Practice',
      'Amount Due',
      'Payment Status',
      'Cell Phone',
      'NOTES'
    ]
  ];

  const studentRows = candidateIDs.map(candidateID => {
    const candidate = candidates[candidateID];
    const applicationType = applicationTypes[candidate.applicationTypeID];
    const { mergedFormSetup } = candidate;

    return [
      candidate.name,
      candidate.company,
      applicationType.name,
      mergedFormSetup.coreEnabled ? 'x' : '',
      mergedFormSetup.writtenSWEnabled ? 'x' : '',
      mergedFormSetup.writtenFXEnabled ? 'x' : '',
      mergedFormSetup.practicalSWEnabled ? 'x' : '',
      mergedFormSetup.practicalFXEnabled ? 'x' : '',
      candidate.practiceHours,
      `$ ${candidate.amountDue || '-'}`,
      candidate.paymentStatus,
      candidate.cellNumber,
      candidate.instructorNotes
    ];
  });

  const classTotalsRow = [
    'Total Candidates',
    candidateIDs.length,
    '',
    state.numCoreExam,
    state.numCranesWrittenSW,
    state.numCranesWrittenFX,
    state.numCranesPracticalSW,
    state.numCranesPracticalFX,
    '',
    `$ ${state.totalDue}`
  ];

  const filename = 'written_class_report.xlsx';
  const wsName = 'Written Class Report';

  const classRosterRows = [...classTopRows, ...studentRows, classTotalsRow];

  const data = [...testSessionRows, [], ...classRosterRows];

  const classTopRowStyle = {
    range: 'A10:M10',
    style: {
      fill: {
        fillType: 'solid',
        startColor: {
          argb: 'FF94C954'
        }
      }
    }
  };

  const mainTableBorderStyle = {
    range: `A10:M${candidateIDs.length + 11}`,
    style: {
      borders: {
        allBorders: {
          borderStyle: 'thin'
        }
      }
    }
  };

  const studentRowsGeneralStyle = candidateIDs.reduce((acc, candidateID, index) => {
    const row = index + 11;

    const centerColumns = {
      range: `D${row}:H${row}`,
      style: {
        alignment: {
          horizontal: 'center'
        }
      }
    };

    if (index % 2 !== 0) {
      return [
        ...acc,
        centerColumns,
        {
          range: `A${row}:M${row}`,
          style: {
            fill: {
              fillType: 'solid',
              startColor: {
                argb: 'FFDBDCDE'
              }
            }
          }
        }
      ];
    }

    return [...acc, centerColumns];
  }, []);

  const studentRowsConditionalStyle = candidateIDs.map((candidateID, index) => {
    const candidate = candidates[candidateID];

    return getPaymentStatusConditionalStyle(candidate.paymentStatus, 11 + index, 'K');
  });

  const classTotalsRowStyle = {
    range: `A${11 + candidateIDs.length}:M${11 + candidateIDs.length}`,
    style: {
      fill: {
        fillType: 'solid',
        startColor: {
          argb: 'FF94C954'
        }
      }
    }
  };

  const styles = [
    mainTableBorderStyle,
    classTopRowStyle,
    ...studentRowsGeneralStyle,
    ...studentRowsConditionalStyle,
    classTotalsRowStyle
  ];

  const mergedCells = ['G9:H9'];

  return {
    data,
    filename,
    wsName,
    styles,
    mergedCells
  };
};

const downloadWrittenClassReport = () => (dispatch, getState) => {
  const rawState = getState();
  const spreadsheetData = prepareWrittenClassReportData(rawState);

  axios
    .post('/admin/testsession/render-spreadsheet', spreadsheetData)
    .then(response => {
      console.log(response.data.link);
      window.location.href = response.data.link;
    })
    .catch(err => {
      console.error(err);
    });
};

export default downloadWrittenClassReport;
