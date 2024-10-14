import axios from 'axios';
import { prepareMainTableData } from '../../lib/helpers';

const downloadNLCSummaryReport = () => (dispatch, getState) => {
  const state = getState();
  const { applicationTypes, testSession } = state;

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

  const { candidateIDs, candidates } = prepareMainTableData(state);

  const rows = candidateIDs.reduce((acc, candidateID) => {
    const candidate = candidates[candidateID];
    const applicationType = applicationTypes[candidate.applicationTypeID];

    return [
      ...acc,
      [
        candidate.name,
        applicationType.name,
        candidate.mergedFormSetup.coreEnabled ? 'X' : '',
        candidate.mergedFormSetup.writtenSWEnabled ? 'X' : '',
        candidate.mergedFormSetup.writtenFXEnabled ? 'X' : '',
        candidate.grades.W_EXAM_CORE,
        candidate.grades.W_EXAM_TLL,
        candidate.grades.W_EXAM_TSS,
        candidate.mergedFormSetup.practicalSWEnabled ? 'X' : '',
        candidate.mergedFormSetup.practicalFXEnabled ? 'X' : '',
        candidate.grades.P_TELESCOPIC_TLL,
        candidate.grades.P_TELESCOPIC_TSS,
        `$${candidate.customerCharges.toLocaleString(undefined, {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        })}`,
        candidate.paymentStatus,
        candidate.invoiceNumber
      ]
    ];
  }, []);

  const totalsRow = candidateIDs.reduce(
    (acc, candidateID) => {
      const candidate = candidates[candidateID];

      return [
        acc[0],
        acc[1] + 1,
        candidate.mergedFormSetup.coreEnabled ? acc[2] + 1 : acc[2],
        candidate.mergedFormSetup.writtenSWEnabled ? acc[3] + 1 : acc[3],
        candidate.mergedFormSetup.writtenFXEnabled ? acc[4] + 1 : acc[4],
        candidate.grades.W_EXAM_CORE === '--' ? acc[5] : acc[5] + 1,
        candidate.grades.W_EXAM_TLL === '--' ? acc[6] : acc[6] + 1,
        candidate.grades.W_EXAM_TSS === '--' ? acc[7] : acc[7] + 1,
        candidate.mergedFormSetup.practicalSWEnabled ? acc[8] + 1 : acc[8],
        candidate.mergedFormSetup.practicalFXEnabled ? acc[9] + 1 : acc[9],
        candidate.grades.P_TELESCOPIC_TLL === '--' ? acc[10] : acc[10] + 1,
        candidate.grades.P_TELESCOPIC_TSS === '--' ? acc[11] : acc[11] + 1,
        acc[12] + candidate.customerCharges
      ];
    },
    ['Total Candidates', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
  );

  totalsRow[12] = `$${totalsRow[12].toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })}`;

  const filename = 'NLC_Summary_Report.xlsx';
  const wsName = 'NLC Summary Report';

  const data = [
    ...testSessionRows,
    [
      'Name',
      'Type',
      'Core',
      'SW',
      'FX',
      'Core',
      'Swing',
      'Fixed',
      'Swing',
      'Fixed',
      'Sw Cab',
      'Fx Cab',
      'Customer Charges',
      'Payment Status',
      'Invoice #'
    ],
    ...rows,
    [],
    totalsRow
  ];

  const baseStyles = [
    {
      range: `A${rows.length + 13}:O${rows.length + 13}`,
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

  const candidateRowStyles = rows.reduce((acc, candidateRow, index) => {
    const row = index + 12;
    const newAcc = [];

    if (index % 2 !== 0) {
      newAcc.push({
        range: `A${row}:O${row}`,
        style: {
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FFDBDCDE'
            }
          }
        }
      });
    }

    const createGradeStyles = (gradeRow, column) => {
      switch (candidateRow[gradeRow]) {
        case 'Fail': {
          newAcc.push({
            range: `${column}${row}`,
            style: {
              font: {
                color: {
                  argb: 'FF000000'
                }
              },
              fill: {
                fillType: 'solid',
                startColor: {
                  argb: 'FFFF0000'
                }
              }
            }
          });
          break;
        }
        case 'Pass': {
          newAcc.push({
            range: `${column}${row}`,
            style: {
              font: {
                color: {
                  argb: 'FF000000'
                }
              },
              fill: {
                fillType: 'solid',
                startColor: {
                  argb: 'FF00FF00'
                }
              }
            }
          });
          break;
        }
        case 'Did Not Test':
        case 'SD': {
          newAcc.push({
            range: `${column}${row}`,
            style: {
              font: {
                color: {
                  argb: 'FF000000'
                }
              },
              fill: {
                fillType: 'solid',
                startColor: {
                  argb: 'FFFFFF00'
                }
              }
            }
          });
          break;
        }
        default:
      }
    };

    createGradeStyles(5, 'F');
    createGradeStyles(6, 'G');
    createGradeStyles(7, 'H');
    createGradeStyles(10, 'K');
    createGradeStyles(11, 'L');

    return [...acc, ...newAcc];
  }, []);

  const styles = [...baseStyles, ...candidateRowStyles];

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

export default downloadNLCSummaryReport;
