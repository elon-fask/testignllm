import axios from 'axios';
import { prepareMainTableData, splitCandidateState } from '../../lib/helpers';
import { getPaymentStatusConditionalStyle } from './styles/candidateRowConditionalStyles';

const getStudentRowsGeneralStyle = (candidateIds, offset) => {
  return candidateIds.reduce((acc, candidateId, index) => {
    const row = index + offset;

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
};

const downloadClassReadinessReport = () => (dispatch, getState) => {
  const rawState = getState();
  const { testSession } = rawState;

  const [
    regularCandidateIDs,
    regularCandidates,
    practicalOnlyCandidateIDs,
    practicalOnlyCandidates
  ] = splitCandidateState(rawState);

  const regularTableState = prepareMainTableData({
    ...rawState,
    candidateIDs: regularCandidateIDs,
    candidates: regularCandidates
  });

  const practicalOnlyTableState = prepareMainTableData({
    ...rawState,
    candidateIDs: practicalOnlyCandidateIDs,
    candidates: practicalOnlyCandidates
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

  const regularStudentTopRows = [
    ['Regular Students', '', '', 'Written', '', '', 'Practical'],
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

  const practicalStudentTopRows = [
    ['Practical-Only Students', '', '', 'Written', '', '', 'Practical'],
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

  const regularStudentRows = regularTableState.candidateIDs.map(candidateID => {
    const candidate = regularTableState.candidates[candidateID];
    const applicationType = regularTableState.applicationTypes[candidate.applicationTypeID];
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

  const regularStudentsTotalsRow = [
    'Total Candidates',
    regularTableState.candidateIDs.length,
    '',
    regularTableState.numCoreExam,
    regularTableState.numCranesWrittenSW,
    regularTableState.numCranesWrittenFX,
    regularTableState.numCranesPracticalSW,
    regularTableState.numCranesPracticalFX,
    '',
    `$ ${regularTableState.totalDue}`
  ];

  const practicalStudentRows = practicalOnlyTableState.candidateIDs.map(candidateID => {
    const candidate = practicalOnlyTableState.candidates[candidateID];
    const applicationType = practicalOnlyTableState.applicationTypes[candidate.applicationTypeID];
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

  const practicalStudentsTotalsRow = [
    'Total Candidates',
    practicalOnlyTableState.candidateIDs.length,
    '',
    practicalOnlyTableState.numCoreExam,
    practicalOnlyTableState.numCranesWrittenSW,
    practicalOnlyTableState.numCranesWrittenFX,
    practicalOnlyTableState.numCranesPracticalSW,
    practicalOnlyTableState.numCranesPracticalFX,
    '',
    `$ ${practicalOnlyTableState.totalDue}`
  ];

  const filename = 'Class_readiness_roster.xlsx';
  const wsName = 'Class Readiness Roster';

  const regularStudentRosterRows = [...regularStudentTopRows, ...regularStudentRows, regularStudentsTotalsRow];
  const practicalStudentRosterRows = [...practicalStudentTopRows, ...practicalStudentRows, practicalStudentsTotalsRow];

  const data = [...testSessionRows, [], ...regularStudentRosterRows, [], ...practicalStudentRosterRows];

  const mainTableEndRowOffset = regularTableState.candidateIDs.length + 11;
  const practicalTableStartRowOffset = mainTableEndRowOffset + 3;
  const practicalTableEndRowOffset = practicalTableStartRowOffset + practicalOnlyTableState.candidateIDs.length + 1;

  const mainTableTopRowStyle = {
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

  const practicalTableTopRowStyle = {
    range: `A${practicalTableStartRowOffset}:M${practicalTableStartRowOffset}`,
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
    range: `A10:M${mainTableEndRowOffset}`,
    style: {
      borders: {
        allBorders: {
          borderStyle: 'thin'
        }
      }
    }
  };

  const practicalTableBorderStyle = {
    range: `A${practicalTableStartRowOffset}:M${practicalTableEndRowOffset}`,
    style: {
      borders: {
        allBorders: {
          borderStyle: 'thin'
        }
      }
    }
  };

  const regularStudentRowsGeneralStyle = getStudentRowsGeneralStyle(regularTableState.candidateIDs, 11);
  const practicalStudentRowsGeneralStyle = getStudentRowsGeneralStyle(
    practicalOnlyTableState.candidateIDs,
    practicalTableStartRowOffset + 1
  );

  const regularStudentRowsConditionalStyle = regularTableState.candidateIDs.map((candidateID, index) => {
    const candidate = regularTableState.candidates[candidateID];

    return getPaymentStatusConditionalStyle(candidate.paymentStatus, 11 + index, 'K');
  });

  const practicalStudentRowsConditionalStyle = practicalOnlyTableState.candidateIDs.map((candidateID, index) => {
    const candidate = practicalOnlyTableState.candidates[candidateID];

    return getPaymentStatusConditionalStyle(candidate.paymentStatus, practicalTableStartRowOffset + 1 + index, 'K');
  });

  const mainTableTotalsRowStyle = {
    range: `A${mainTableEndRowOffset}:M${mainTableEndRowOffset}`,
    style: {
      fill: {
        fillType: 'solid',
        startColor: {
          argb: 'FF94C954'
        }
      }
    }
  };

  const practicalTableTotalsRowStyle = {
    range: `A${practicalTableEndRowOffset}:M${practicalTableEndRowOffset}`,
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
    mainTableTopRowStyle,
    practicalTableTopRowStyle,
    mainTableBorderStyle,
    practicalTableBorderStyle,
    ...regularStudentRowsGeneralStyle,
    ...practicalStudentRowsGeneralStyle,
    ...regularStudentRowsConditionalStyle,
    ...practicalStudentRowsConditionalStyle,
    mainTableTotalsRowStyle,
    practicalTableTotalsRowStyle
  ];

  axios
    .post('/admin/testsession/render-spreadsheet', {
      data,
      filename,
      wsName,
      styles
    })
    .then(response => {
      console.log(response.data.link);
      window.location.href = response.data.link;
    })
    .catch(err => {
      console.error(err);
    });
};

export default downloadClassReadinessReport;
