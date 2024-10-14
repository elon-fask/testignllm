import React from 'react';
import { bool, string, number, arrayOf, shape, node } from 'prop-types';
import RaisedButton from 'material-ui/RaisedButton';
import CircularProgress from 'material-ui/CircularProgress';

const ModalContents = ({ isLoadingModalData, testSessionData }) => (
  <div
    style={{
      alignItems: 'center',
      display: 'flex',
      justifyContent: 'center'
    }}
  >
    {isLoadingModalData ? (
      <CircularProgress />
    ) : (
      <table className="table table-striped table-bordered">
        <tbody>
          <ModalRow title="Test Site">{testSessionData.testSite}</ModalRow>
          <ModalRow title="Address">{testSessionData.address}</ModalRow>
          <ModalRow title="Staff">{testSessionData.staff}</ModalRow>
          <ModalRow title="Test Site Coordinator">{testSessionData.testSiteCoordinator}</ModalRow>
          <ModalRow title="Total Seats">{testSessionData.totalSeats}</ModalRow>
          <ModalRow title="Class Stats">
            <div style={{ display: 'flex' }}>
              <div style={{ textAlign: 'right', marginRight: '16px' }}>
                <div>Total Candidates:</div>
                <div>Regular Candidates:</div>
                <div>FX Cab Test Takers:</div>
                <div>SW Cab Test Takers:</div>
                <div>Written-only Candidates:</div>
                <div>Practical-only Candidates:</div>
                <div>Test-only Candidates:</div>
              </div>
              <div>
                <div>{testSessionData.classStats.totalCandidates}</div>
                <div>{testSessionData.classStats.totalRegular}</div>
                <div>{testSessionData.classStats.fx}</div>
                <div>{testSessionData.classStats.sw}</div>
                <div>{testSessionData.classStats.writtenOnly}</div>
                <div>{testSessionData.classStats.practicalOnly}</div>
                <div>{testSessionData.classStats.testOnly}</div>
              </div>
            </div>
          </ModalRow>
          <ModalRow title="Session Type">{testSessionData.sessionType}</ModalRow>
          <ModalRow title="Details">
            Click{' '}
            <a target="_blank" href={`/admin/testsession/view?id=${testSessionData.id}`}>
              here
            </a>{' '}
            to view session
          </ModalRow>
          <ModalRow title="Roster">
            Click{' '}
            <a target="_blank" href={`/admin/candidatesession?i=${testSessionData.idHash}`}>
              here
            </a>{' '}
            to view roster
          </ModalRow>
          <ModalRow title="Spreadsheet">
            <RaisedButton
              label="View Spreadsheet"
              primary
              onClick={() => {
                window.location.href = `/admin/testsession/spreadsheet?id=${testSessionData.id}`;
              }}
            />
          </ModalRow>
          <ModalRow title="Administer Session (CraneTrx)">
            Click{' '}
            <a target="_blank" href={`/cranetrx/#/class/${testSessionData.id}`}>
              here
            </a>{' '}
            to administer session
          </ModalRow>
        </tbody>
      </table>
    )}
  </div>
);

const ModalRow = ({ title, children }) => (
  <tr>
    <th>{title}</th>
    <td>{children}</td>
  </tr>
);

const checklistType = arrayOf(
  shape({
    date_created: string.isRequired,
    id: number.isRequired,
    isArchived: number.isRequired,
    name: string.isRequired,
    type: number.isRequired
  })
);

ModalContents.propTypes = {
  isLoadingModalData: bool.isRequired,
  testSessionData: shape({
    id: number,
    idHash: string,
    address: string,
    date: string,
    sessionType: string,
    staff: string,
    testSite: string,
    testSiteCoordinator: string,
    totalSeats: number,
    totalSeatsTaken: number
  }),
  assignedPreChecklists: checklistType,
  preChecklistTemplates: checklistType,
  assignedPostChecklists: checklistType,
  postChecklistTemplates: checklistType
};

ModalContents.defaultProps = {
  testSessionData: {},
  assignedPreChecklists: [],
  preChecklistTemplates: [],
  assignedPostChecklists: [],
  postChecklistTemplates: []
};

ModalRow.propTypes = {
  title: string.isRequired,
  children: node.isRequired
};

export default ModalContents;
