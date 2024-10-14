const downloadCandidatePhotosZip = () => (dispatch, getState) => {
  const { testSession: { id } } = getState();

  return `/admin/testsession/download-candidate-photos-zip?id=${id}`;
};

export default downloadCandidatePhotosZip;
