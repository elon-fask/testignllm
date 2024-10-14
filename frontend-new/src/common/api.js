import axios from 'axios';

export const apiDeletePracticalTestSchedule = id => {
  return axios.post(`/api/test-session/delete-practical-test-schedule?id=${id}`);
};
