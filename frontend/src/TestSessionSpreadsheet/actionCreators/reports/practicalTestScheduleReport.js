import axios from 'axios';
import { formatMoney } from 'accounting';
import { prepareMainTableData, preparePracticalTestScheduleTableData } from '../../lib/helpers';
import {
  getPaymentStatusConditionalStyle,
  getScheduleRowConditionalStyle
} from './styles/candidateRowConditionalStyles';

const topRow = ['Practical Test Schedule'];

const headerRow = [
  'Day',
  'Date',
  'Time',
  'Name',
  'Company',
  'Type',
  'New or Retest',
  'SW Cab',
  'FX Cab',
  'Practice',
  'Amount Due',
  'Payment Status',
  'Cell Phone',
  'Notes'
];

export const preparePracticalTestScheduleReportData = rawState => {
  const { applicationTypes } = rawState;
  const filteredState = {
    ...rawState,
    ...preparePracticalTestScheduleTableData(rawState)
  };
  const tableState = prepareMainTableData(filteredState);
  const { candidateIDs, candidates } = tableState;

  const scheduleRows = candidateIDs.map(id => {
    const candidate = candidates[id];
    const applicationType = applicationTypes[candidate.applicationTypeID];
    const { day, date, time, practiceTime, new_or_retest } = candidate.testSchedule;

    let newOrRetest = '';

    if (new_or_retest === 'NEW') {
      newOrRetest = 'New';
    }

    if (new_or_retest === 'RETEST') {
      newOrRetest = 'Retest';
    }

    if (new_or_retest === 'NONE') {
      newOrRetest = 'None';
    }

    return [
      day,
      date,
      time,
      candidate.name,
      candidate.company,
      applicationType.name,
      newOrRetest,
      candidate.numCranesSW ? 'x' : '',
      candidate.numCranesFX ? 'x' : '',
      practiceTime,
      formatMoney(candidate.amountDue),
      candidate.paymentStatus,
      candidate.cellNumber,
      candidate.instructorNotes
    ];
  });

  const totalsRow = [
    `Total Candidates: ${candidateIDs.length}`,
    '',
    '',
    '',
    '',
    '',
    '',
    tableState.numCranesPracticalSW,
    tableState.numCranesPracticalFX
  ];

  const data = [topRow, headerRow, ...scheduleRows, totalsRow];

  const endRowNum = candidateIDs.length + 3;
  const mergedCells = ['A1:N1', `A${endRowNum}:C${endRowNum}`];

  const wholeTableBorderStyle = {
    range: `A1:N${candidateIDs.length + 3}`,
    style: {
      borders: {
        allBorders: {
          borderStyle: 'thin'
        }
      }
    }
  };

  const topRowStyle = {
    range: 'A1:A1',
    style: {
      fill: {
        fillType: 'solid',
        startColor: {
          argb: 'FFC000'
        }
      }
    }
  };

  const headerRowStyle = {
    range: 'A2:N2',
    style: {
      fill: {
        fillType: 'solid',
        startColor: {
          argb: 'FFFF00'
        }
      }
    }
  };

  const scheduleRowsConditionalStyles = candidateIDs.reduce((acc, id, index) => {
    const candidate = candidates[id];
    const newAcc = acc;

    const offset = index + 3;

    const partialScheduleRowStyle = getScheduleRowConditionalStyle(candidate.testSchedule.day);

    if (partialScheduleRowStyle) {
      const scheduleRowStyleHead = {
        range: `A${offset}:K${offset}`,
        style: partialScheduleRowStyle
      };

      const scheduleRowStyleTail = {
        range: `M${offset}:N${offset}`,
        style: partialScheduleRowStyle
      };

      newAcc.push(scheduleRowStyleHead);
      newAcc.push(scheduleRowStyleTail);
    }

    const paymentStatusStyle = getPaymentStatusConditionalStyle(candidate.paymentStatus, offset, 'L');

    if (paymentStatusStyle) {
      newAcc.push(paymentStatusStyle);
    }

    return [...acc];
  }, []);

  const totalsRowStyle = {
    range: `A${candidateIDs.length + 3}:N${candidateIDs.length + 3}`,
    style: {
      fill: {
        fillType: 'solid',
        startColor: {
          argb: 'FFFF00'
        }
      }
    }
  };

  const styles = [wholeTableBorderStyle, topRowStyle, headerRowStyle, ...scheduleRowsConditionalStyles, totalsRowStyle];

  const filename = 'Practical_Exam_Only_Candidates.xlsx';
  const wsName = 'Practical Exam Only Candidates';

  return {
    data,
    filename,
    wsName,
    styles,
    mergedCells
  };
};

const downloadPracticalTestScheduleReport = () => (dispatch, getState) => {
  const rawState = getState();
  const spreadsheetData = preparePracticalTestScheduleReportData(rawState);

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

export default downloadPracticalTestScheduleReport;
