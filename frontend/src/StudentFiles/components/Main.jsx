import React, { Fragment } from 'react';
import _groupBy from 'lodash/groupBy';
import Panel from '../../common/components/bootstrap/Panel';
import ImageLoader from '../../common/components/bootstrap/ImageLoader';

const Main = props => {
  const photoUrl = `${props.cPhotoBaseUrl}${props.candidate.photo}`;
  const { trainingSessions, declinedTests } = props.candidate;
  const declinedTestsBySession = _groupBy(declinedTests, 'test_session_id');

  return (
    <Fragment>
      <Panel heading="Student Photo">
        {props.candidate.photo ? (
          <Fragment>
            <ImageLoader src={photoUrl} alt={props.candidate.fullName} />
            <a href={photoUrl} className="btn btn-primary">
              <i className="fa fa-cloud-download" aria-hidden="true" /> Download
            </a>
          </Fragment>
        ) : (
          <p>No photo uploaded</p>
        )}
      </Panel>
      <div className="panel panel-default">
        <div className="panel-heading">
          <h4>Score Sheet Photos</h4>
        </div>
        <div className="panel-body" style={{ display: 'flex', flexWrap: 'wrap' }}>
          {props.candidate.scoreSheetPhotos.length > 0 ? (
            props.candidate.scoreSheetPhotos.map(photo => {
              const scoreSheetUrl = `${props.cPhotoBaseUrl}${photo.s3_key}`;

              return (
                <div key={photo.id} style={{ marginRight: '20px' }}>
                  <ImageLoader src={scoreSheetUrl} alt="Score Sheet Photo" />
                  <a href={scoreSheetUrl} className="btn btn-primary">
                    <i className="fa fa-cloud-download" aria-hidden="true" /> Download
                  </a>
                </div>
              );
            })
          ) : (
            <p>No score sheet photos uploaded</p>
          )}
        </div>
      </div>
      <div className="panel panel-default">
        <div className="panel-heading">
          <h4>Training Sessions</h4>
        </div>
        <div className="panel-body">
          {trainingSessions.length > 0 ? (
            trainingSessions.map(tSession => {
              const attestationUrl = `${props.cPhotoBaseUrl}${tSession.attestation_s3_key}`;

              return (
                <div key={tSession.id} style={{ display: 'flex', marginBottom: '20px' }}>
                  <div style={{ marginRight: '20px' }}>
                    {tSession.attestation_s3_key ? (
                      <Fragment>
                        <h5>Attestation</h5>
                        <ImageLoader src={attestationUrl} alt="Attestation Image" />
                        <a href={attestationUrl} className="btn btn-primary">
                          <i className="fa fa-cloud-download" aria-hidden="true" /> Download
                        </a>
                      </Fragment>
                    ) : (
                      <p>No attestation uploaded</p>
                    )}
                  </div>
                  <div>
                    <h5>Training Photos</h5>
                    <div style={{ display: 'flex', flexWrap: 'wrap' }}>
                      {tSession.trainingPhotos.length > 0 ? (
                        tSession.trainingPhotos.map(trainingPhoto => {
                          const tPhotoUrl = `${props.cPhotoBaseUrl}${trainingPhoto.s3_key}`;

                          return (
                            <div key={trainingPhoto.id} style={{ marginRight: '20px' }}>
                              <ImageLoader src={tPhotoUrl} alt="Training Photo" />
                              <a href={tPhotoUrl} className="btn btn-primary">
                                <i className="fa fa-cloud-download" aria-hidden="true" /> Download
                              </a>
                            </div>
                          );
                        })
                      ) : (
                        <p>No training photos uploaded</p>
                      )}
                    </div>
                  </div>
                </div>
              );
            })
          ) : (
            <p>No training session photos uploaded</p>
          )}
        </div>
      </div>
      <div className="panel panel-default">
        <div className="panel-heading">
          <h4>Declined Practical Exams</h4>
        </div>
        <div className="panel-body">
          {Object.keys(declinedTestsBySession).length > 0 ? (
            Object.keys(declinedTestsBySession).map(testSessionId => {
              const dTests = declinedTestsBySession[testSessionId];
              const declinedSWTests = dTests.filter(dTest => dTest.crane === 'sw');
              const declinedFXTests = dTests.filter(dTest => dTest.crane === 'fx');

              return (
                <div key={testSessionId} style={{ display: 'flex', flexWrap: 'wrap' }}>
                  {declinedSWTests.length > 0 && (
                    <div>
                      <div>SW Cab</div>
                      {declinedSWTests.map(dTest => {
                        const attestationUrl = `${props.cPhotoBaseUrl}${dTest.s3_key}`;

                        return (
                          <div key={dTest.id} style={{ marginRight: '20px' }}>
                            <ImageLoader src={attestationUrl} alt="Training Photo" />
                            <a href={attestationUrl} className="btn btn-primary">
                              <i className="fa fa-cloud-download" aria-hidden="true" /> Download
                            </a>
                          </div>
                        );
                      })}
                    </div>
                  )}
                  {declinedFXTests.length > 0 && (
                    <div>
                      <div>FX Cab</div>
                      {declinedFXTests.map(dTest => {
                        const attestationUrl = `${props.cPhotoBaseUrl}${dTest.s3_key}`;

                        return (
                          <div key={dTest.id} style={{ marginRight: '20px' }}>
                            <ImageLoader src={attestationUrl} alt="Training Photo" />
                            <a href={attestationUrl} className="btn btn-primary">
                              <i className="fa fa-cloud-download" aria-hidden="true" /> Download
                            </a>
                          </div>
                        );
                      })}
                    </div>
                  )}
                </div>
              );
            })
          ) : (
            <p>No declined Practical Exam attestations uploaded</p>
          )}
        </div>
      </div>
    </Fragment>
  );
};

export default Main;
