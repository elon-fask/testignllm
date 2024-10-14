import * as React from 'react';
import axios from 'axios';
import styled from 'styled-components';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUpload } from '@fortawesome/free-solid-svg-icons/faUpload';
import { faSpinner } from '@fortawesome/free-solid-svg-icons/faSpinner';
import { readTestSessionInfoFromWorksheetData, readRegularCandidateRosterFromWorksheetData } from './helpers';

const WorksheetSection = styled.div`
  display: flex;
  flex-wrap: wrap;
  margin-top: 1rem;

  & > div:hover {
    cursor: pointer;
  }
`;

function Main() {
  const fileField = React.useRef<HTMLInputElement>(null);
  const [filename, setFilename] = React.useState(null);
  const [isSubmitting, setIsSubmitting] = React.useState(false);
  const [worksheets, setWorksheets] = React.useState(null);
  const [worksheetOptions, setWorksheetOptions] = React.useState({});

  const handleFileSelect = async e => {
    setFilename(e.currentTarget.files[0].name);
  };

  const handleSubmit = async e => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('file', fileField.current.files[0]);
    formData.append('options[sheetNamesOnly]', '1');

    setIsSubmitting(true);

    try {
      const { data }: any = await axios.post('/admin/candidates/bulk-register-legacy', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      setIsSubmitting(false);
      setWorksheets(data);
    } catch (e) {
      setIsSubmitting(false);
      console.error(e);
    }
  };

  const handleDeleteWorksheet = e => {
    const index = parseInt(e.currentTarget.dataset.index, 10);
    setWorksheets([...worksheets.slice(0, index), ...worksheets.slice(index + 1)]);
  };

  const uploadWorkSheet = async worksheet => {
    const formData = new FormData();
    formData.append('file', fileField.current.files[0]);
    formData.append('options[worksheet]', worksheet);

    try {
      const { data: worksheetData }: any = await axios.post('/admin/candidates/bulk-register-legacy', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      const testSessionInfo = readTestSessionInfoFromWorksheetData(worksheetData);
      const regularCandidateRoster = readRegularCandidateRosterFromWorksheetData(worksheetData);
      const { data: testSessionData }: any = axios.post('/api/legacy/upload', {
        testSessionInfo,
        regularCandidateRoster
      });

      console.log(testSessionInfo);
      console.log(regularCandidateRoster);
    } catch (e) {
      console.error(e);
    }
  };

  const handleLoadWorksheetData = async e => {
    e.preventDefault();

    worksheets.forEach(async worksheet => {
      await uploadWorkSheet(worksheet);
    });
  };

  return (
    <section className="section">
      <div className="container">
        <form onSubmit={handleSubmit} style={{ display: 'flex' }}>
          <div className="file has-name" style={{ marginRight: '1rem' }}>
            <label className="file-label">
              <input className="file-input" type="file" name="file" ref={fileField} onChange={handleFileSelect} />
              <span className="file-cta">
                <span className="file-icon">
                  <FontAwesomeIcon icon={faUpload} />
                </span>
                <span className="file-label">Choose a file…</span>
              </span>
              {filename && <span className="file-name">{filename}</span>}
            </label>
          </div>
          <button type="submit" className="button is-primary">
            {isSubmitting ? <FontAwesomeIcon icon={faSpinner} spin={true} /> : 'Submit'}
          </button>
        </form>
      </div>
      {worksheets && (
        <>
          <WorksheetSection className="container" style={{ display: 'flex', flexWrap: 'wrap', marginTop: '1rem' }}>
            {worksheets.map((worksheet, i) => (
              <div style={{ margin: '0.5rem' }}>
                <span key={worksheet} className="tag is-primary is-medium">
                  {worksheet}
                  <button className="delete is-small" data-index={i} onClick={handleDeleteWorksheet} />
                </span>
              </div>
            ))}
          </WorksheetSection>
          <div className="container">
            <button className="button is-primary" onClick={handleLoadWorksheetData}>
              Load Worksheet Data
            </button>
          </div>
        </>
      )}
    </section>
  );
}

export default Main;
