import axios from 'axios';

const downloadApplicationFormsZip = () => (dispatch, getState) => {
  const { testSession: { id } } = getState();
  return axios.get(`/admin/testsession/download-application-forms-zip?id=${id}`);
};

export default downloadApplicationFormsZip;
