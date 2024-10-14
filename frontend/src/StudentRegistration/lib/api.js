import axios from 'axios';

export const apiCheckKeyword = keyword => axios.post(`/site/check-password?password=${keyword}`);
