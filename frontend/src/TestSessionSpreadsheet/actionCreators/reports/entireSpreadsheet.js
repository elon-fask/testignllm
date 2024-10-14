import axios from 'axios';
import { prepareMainTableData, splitCandidateState } from '../../lib/helpers';
import { getCandidateRowConditionalStyles } from './styles/candidateRowConditionalStyles';

const topRow = [
  '',
  '',
  '',
  'Written',
  '',
  '',
  'Practical',
  '',
  'NCCCO Fees',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  'Grades Written',
  '',
  '',
  'Grades Practical',
  ''
];

const headerRow = [
  'Name',
  'Company',
  'Type',
  'Core',
  'SW',
  'FX',
  'SW Cab',
  'FX Cab',
  'Practical Charges',
  'Practical Retest',
  'Testing',
  'Late Fee',
  'Incomplete Fee',
  'Walk-in Fee',
  'Other Fee',
  'Practice Time Charges',
  'Customer Charges',
  'Paid',
  'Amount Due',
  'Payment Status',
  'Invoice #',
  'Purchase Order #',
  'Core',
  'SW',
  'FX',
  'SW Cab',
  'FX Cab'
];

const setupCandidateRows = (candidates, candidateIDs, applicationTypes) =>
  candidateIDs.map(candidateID => {
    const candidate = candidates[candidateID];
    const applicationType = applicationTypes[candidate.applicationTypeID];

    return [
      candidate.name,
      candidate.company,
      applicationType.name,
      candidate.mergedFormSetup.coreEnabled ? 'x' : '',
      candidate.mergedFormSetup.writtenSWEnabled ? 'x' : '',
      candidate.mergedFormSetup.writtenFXEnabled ? 'x' : '',
      candidate.mergedFormSetup.practicalSWEnabled ? candidate.numCranesSW : '',
      candidate.mergedFormSetup.practicalFXEnabled ? candidate.numCranesFX : '',
      candidate.practicalCharges ? `$${candidate.practicalCharges}` : '--',
      candidate.practicalRetestFee ? `$${candidate.practicalRetestFee}` : '--',
      candidate.writtenCharges ? `$${candidate.writtenCharges}` : '--',
      candidate.lateFee ? `$${candidate.lateFee}` : '--',
      candidate.incompleteFee ? `$${candidate.incompleteFee}` : '--',
      candidate.walkInFee ? `$${candidate.walkInFee}` : '--',
      candidate.otherFee ? `$${candidate.otherFee}` : '--',
      `$${candidate.practiceTimeCharge.toString()}`,
      `$${candidate.customerCharges.toString()}`,
      `$${candidate.amountPaid.toString()}`,
      `$${candidate.amountDue.toString()}`,
      candidate.paymentStatus,
      candidate.invoiceNumber,
      candidate.purchaseOrderNumber,
      candidate.grades.W_EXAM_CORE,
      candidate.grades.W_EXAM_TLL,
      candidate.grades.W_EXAM_TSS,
      candidate.grades.P_TELESCOPIC_TLL,
      candidate.grades.P_TELESCOPIC_TSS
    ];
  });

const downloadEntireSpreadsheet = () => (dispatch, getState) => {
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

  const state = prepareMainTableData(rawState);
  const { candidateIDs, candidates, applicationTypes } = state;

  const testSessionRows = [
    ['Test Site Name:', testSession.testSiteName],
    ['Test Site Coordinator:', testSession.testSiteCoordinator],
    ['Test Site Address:', testSession.testSiteAddress],
    ['Test Site Number:', testSession.testSiteNumber],
    ['Test Date:', testSession.testingDate],
    ['Instructor:', testSession.instructor],
    ['Practical Examiner:', testSession.practicalExaminer],
    ['Proctor:'],
    ['Practical Test Site Code:', testSession.practicalTestSiteCode],
    []
  ];

  const regularCandidateRows = setupCandidateRows(
    regularTableState.candidates,
    regularTableState.candidateIDs,
    rawState.applicationTypes
  );

  const practicalOnlyCandidateRows = setupCandidateRows(
    practicalOnlyTableState.candidates,
    practicalOnlyTableState.candidateIDs,
    rawState.applicationTypes
  );

  const regularTableTotalsRow = [
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    `$${regularTableState.totalPracticalCharges}`,
    `$${regularTableState.totalPracticalRetestFee}`,
    `$${regularTableState.totalWrittenNcccoFees}`,
    `$${regularTableState.totalLateFee}`,
    `$${regularTableState.totalIncompleteFee}`,
    `$${regularTableState.totalWalkInFee}`,
    `$${regularTableState.totalOtherFee}`,
    `$${regularTableState.totalPracticeTimeCharges}`,
    `$${regularTableState.totalCustomerCharges}`,
    `$${regularTableState.totalPaid}`,
    `$${regularTableState.totalDue}`,
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    ''
  ];

  const practicalOnlyTableTotalsRow = [
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    `$${practicalOnlyTableState.totalPracticalCharges}`,
    `$${practicalOnlyTableState.totalPracticalRetestFee}`,
    `$${practicalOnlyTableState.totalWrittenNcccoFees}`,
    `$${practicalOnlyTableState.totalLateFee}`,
    `$${practicalOnlyTableState.totalIncompleteFee}`,
    `$${practicalOnlyTableState.totalWalkInFee}`,
    `$${practicalOnlyTableState.totalOtherFee}`,
    `$${practicalOnlyTableState.totalPracticeTimeCharges}`,
    `$${practicalOnlyTableState.totalCustomerCharges}`,
    `$${practicalOnlyTableState.totalPaid}`,
    `$${practicalOnlyTableState.totalDue}`,
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    ''
  ];

  const totalsRow = [
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    `$${regularTableState.totalPracticalCharges + practicalOnlyTableState.totalPracticalCharges}`,
    `$${regularTableState.totalPracticalRetestFee + practicalOnlyTableState.totalPracticalRetestFee}`,
    `$${regularTableState.totalWrittenNcccoFees + practicalOnlyTableState.totalWrittenNcccoFees}`,
    `$${regularTableState.totalLateFee + practicalOnlyTableState.totalLateFee}`,
    `$${regularTableState.totalIncompleteFee + practicalOnlyTableState.totalIncompleteFee}`,
    `$${regularTableState.totalWalkInFee + practicalOnlyTableState.totalWalkInFee}`,
    `$${regularTableState.totalOtherFee + practicalOnlyTableState.totalOtherFee}`,
    `$${regularTableState.totalPracticeTimeCharges + practicalOnlyTableState.totalPracticeTimeCharges}`,
    `$${regularTableState.totalCustomerCharges + practicalOnlyTableState.totalCustomerCharges}`,
    `$${regularTableState.totalPaid + practicalOnlyTableState.totalPaid}`,
    `$${regularTableState.totalDue + practicalOnlyTableState.totalDue}`,
    '',
    '',
    '',
    '',
    '',
    '',
    '',
    ''
  ];

  const summaryRows = [
    [
      'Total Candidates:',
      candidateIDs.length,
      '',
      'Total NCCCO Practical Exam and Retest Fees:',
      `$${state.totalNcccoPracticalExamAndRetestFees}`,
      '',
      'Total NCCCO Written Test Fees:',
      `$${state.totalWrittenNcccoFees}`
    ],
    [
      'Total SW Cranes:',
      state.numCranesPracticalSW,
      '',
      '50% Provided by NCCCO:',
      `$${state.totalNcccoPracticalExamAndRetestFees / 2}`,
      '',
      '50% Provided by NCCCO:',
      `$${state.totalWrittenNcccoFees / 2}`
    ],
    [
      'Total FX Cranes:',
      state.numCranesPracticalFX,
      '',
      'Total NCCCO Practical Fees:',
      `$${state.totalNcccoPracticalFees}`,
      '',
      'Total NCCCO Written Other Fees:',
      `$${state.totalNcccoWrittenOtherFees}`
    ]
  ];

  if (regularTableState.lessThanFee > 0) {
    summaryRows[3] = ['', '', '', '', '', '', 'Applicable Less Than Fee:', `$${regularTableState.lessThanFee}`];
    summaryRows[4] = ['', '', '', '', '', '', 'Total NCCCO Test Fees Credit:', '$0'];
    summaryRows[5] = ['', '', '', '', '', '', 'Total NCCCO Written Fees:', `$${state.totalNcccoWrittenFees}`];
  } else {
    summaryRows[3] = ['', '', '', '', '', '', 'Total NCCCO Test Fees Credit:', '$0'];
    summaryRows[4] = ['', '', '', '', '', '', 'Total NCCCO Written Fees:', `$${state.totalNcccoWrittenFees}`];
  }

  const filename = 'Crane_Admin_Spreadsheet.xlsx';
  const wsName = 'Crane Admin Spreadsheet';

  const data = [
    ...testSessionRows,
    topRow,
    headerRow,
    ...regularCandidateRows,
    regularTableTotalsRow,
    [],
    ...practicalOnlyCandidateRows,
    practicalOnlyTableTotalsRow,
    [],
    ['', '', '', '', '', '', '', '', ...headerRow.slice(8, 19)],
    totalsRow,
    [],
    ...summaryRows
  ];

  const totalsRowStyles = [
    {
      range: `I${13 + regularCandidateRows.length}:S${13 + regularCandidateRows.length}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    },
    {
      range: `I${15 + regularCandidateRows.length + practicalOnlyCandidateRows.length}:S${15 +
        regularCandidateRows.length +
        practicalOnlyCandidateRows.length}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    }
  ];

  const bottomOffset = regularCandidateRows.length + practicalOnlyCandidateRows.length + 15;

  const summaryTableStyles = [
    {
      range: `A${bottomOffset + 5}:B${bottomOffset + 5}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `D${bottomOffset + 5}:E${bottomOffset + 5}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `G${bottomOffset + 5}:H${bottomOffset + 5}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `A${bottomOffset + 6}:B${bottomOffset + 6}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    },
    {
      range: `D${bottomOffset + 6}:E${bottomOffset + 6}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    },
    {
      range: `G${bottomOffset + 6}:H${bottomOffset + 6}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    },
    {
      range: `A${bottomOffset + 7}:B${bottomOffset + 7}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `D${bottomOffset + 7}:E${bottomOffset + 7}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `G${bottomOffset + 7}:H${bottomOffset + 7}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `G${bottomOffset + 8}:H${bottomOffset + 8}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    },
    {
      range: `G${bottomOffset + 9}:H${bottomOffset + 9}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    }
  ];

  if (state.lessThanFee > 0) {
    summaryTableStyles.push({
      range: `G${bottomOffset + 10}:H${bottomOffset + 10}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    });
  }

  const baseStyles = [
    ...totalsRowStyles,
    ...summaryTableStyles,
    {
      range: 'A12:AA12',
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `I${bottomOffset + 2}:S${bottomOffset + 2}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FF94C954'
          }
        }
      }
    },
    {
      range: `I${bottomOffset + 3}:S${bottomOffset + 3}`,
      style: {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'FFD8E3BC'
          }
        }
      }
    }
  ];

  const regularCandidateRowStyles = getCandidateRowConditionalStyles(regularCandidateRows, 13);
  const practicalCandidateRowStyles = getCandidateRowConditionalStyles(
    practicalOnlyCandidateRows,
    regularCandidateRows.length + 15
  );

  const styles = [...baseStyles, ...regularCandidateRowStyles, ...practicalCandidateRowStyles];

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

export default downloadEntireSpreadsheet;
