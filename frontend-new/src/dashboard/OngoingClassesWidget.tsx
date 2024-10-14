import * as React from 'react';
import { staffList, staffListDesc } from '../common/user';
import { OngoingClass } from './Main';
import Widget from '../common/components/Widget';

interface OngoingClassesWidgetProps {
  ongoingClasses: OngoingClass[];
}

function OngoingClassesWidget(props: OngoingClassesWidgetProps) {
  const links = [
    <li key={1}>
      <a href="/admin/calendar">
        Go to Calendar <i className="fa fa-external-link" />
      </a>
    </li>
  ];

  return (
    <Widget heading="Ongoing Classes" links={links}>
      <table className="table table-striped">
        <thead>
          <tr>
            <th>Session Name</th>
            <th>Location</th>
            <th>Number of Candidates</th>
            <th style={{ textAlign: 'center' }}>Assigned Staff</th>
            <th>Pending Transactions</th>
            <th>Links</th>
          </tr>
        </thead>
        <tbody>
          {props.ongoingClasses.map(({ id, name, location, numCandidates, staff, pendingTransactions }) => (
          
  <tr key={id}>
              <td>{name}</td>
              <td>{location}</td>
              <td style={{ textAlign: 'center' }}>{numCandidates}</td>
              <td>
                <div>
                  {staffList.reduce((acc, role) => {
                    if (staff[role] && staff[role] !== '-') {
                      return [
                        ...acc,
                        <div key={role} style={{ display: 'flex', justifyContent: 'space-between' }}>
                          <div>{staffListDesc[role]}</div>
                          <div style={{ fontWeight: 'bold' }}>{staff[role]}</div>
                        </div>
                      ];
                    }
                    return acc;
                  }, [])}
                </div>
              </td>
              <td style={{ textAlign: 'center' }}>{pendingTransactions}</td>
              <td>
                <div>
                  <a
                    href={`/admin/testsession/spreadsheet?id=${id}`}
                    className="btn btn-primary"
                    style={{ marginRight: '8px' }}
                  >
                    Spreadsheet
                  </a>
                  <a href={`/admin/testsession/update?id=${id}`} target="_blank" className="btn btn-primary">
                    Edit
                  </a>
                </div>
              </td>
            </tr>

          ))}
        </tbody>
      </table>
    </Widget>
  );
}

export default OngoingClassesWidget;
