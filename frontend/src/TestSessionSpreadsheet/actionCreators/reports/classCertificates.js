import axios from 'axios';

const downloadClassCertificates = (instructorName, certDate) => (dispatch, getState) => {
  const { testSession: { id } } = getState();
  return axios.post(`/api/test-session/class-certificate?id=${id}`, {
    instructorName,
    certDate
  });
};

export default downloadClassCertificates;
