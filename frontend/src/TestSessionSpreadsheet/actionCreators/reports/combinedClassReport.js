import axios from 'axios';
import { prepareWrittenClassReportData } from './writtenClassReport';
import { preparePracticalClassReportData } from './practicalClassReport';
import { preparePracticalTestScheduleReportData } from './practicalTestScheduleReport';

const downloadCombinedClassReport = () => (dispatch, getState) => {
  const rawState = getState();
  const writtenClassReportWsData = prepareWrittenClassReportData(rawState);
  const practicalClassReportWsData = preparePracticalClassReportData(rawState);
  const practicalTestScheduleReportWsData = preparePracticalTestScheduleReportData(rawState);

  axios
    .post('/admin/testsession/render-spreadsheet?multiple=1', {
      filename: 'combined_class_report.xlsx',
      worksheets: [writtenClassReportWsData, practicalClassReportWsData, practicalTestScheduleReportWsData]
    })
    .then(response => {
      console.log(response.data.link);
      window.location.href = response.data.link;
    })
    .catch(err => {
      console.error(err);
    });
};

export default downloadCombinedClassReport;
