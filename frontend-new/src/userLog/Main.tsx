import * as React from 'react';
import ApolloClient from 'apollo-boost';
import gql from 'graphql-tag';
import moment from 'moment';
import { actionStr, sourceStr, UserLog } from '../common/userLogs';
import Spinner from '../common/components/Spinner';

const { useEffect, useReducer } = React;

declare const apiUrl: string;
declare const googleMapsApiKey: string;

const client = new ApolloClient({
  uri: apiUrl
});

const query = gql`
  {
    users {
      id
      firstName
      lastName
    }
    userLogs {
      id
      userId
      type
      location {
        lat
        long
        reverseGeocode
      }
      source
      details {
        candidateId
        candidate {
          id
          md5Hash
          firstName
          lastName
        }
        testSessionId
        testSession {
          id
          sessionNumber
          startDate
          testSite {
            city
            state
          }
        }
        checklistStepId
        geolocationErrorCode
      }
      createdAt
      updatedAt
    }
  }
`;

const reducer = (state: any, action: any) => {
  switch (action.type) {
    case 'SET_STATE': {
      return {
        ...state,
        ...action.payload
      };
    }
    case 'SET_USER_LOGS': {
      return {
        ...state,
        userLogs: action.payload
      };
    }
    default: {
      return state;
    }
  }
};

function Main() {
  const [state, dispatch] = useReducer(reducer, { userLogs: [], isLoading: true });

  useEffect(() => {
    client.query({ query }).then(({ data }: { data: any }) => {
      if (!data.userLogs) {
        throw new Error('No user log data received');
      }

      const users = data.users.reduce((acc: any, user: any) => {
        return {
          ...acc,
          [user.id]: `${user.firstName} ${user.lastName}`
        };
      }, {});

      const userLogs = data.userLogs.map((userLog: UserLog) => {
        return {
          id: userLog.id,
          name: users[userLog.userId],
          type: actionStr[userLog.type],
          source: sourceStr[userLog.source],
          location: userLog.location.reverseGeocode,
          details: {
            ...userLog.details,
            ...userLog.location
          },
          createdAt: moment(userLog.createdAt, 'x').format('MM/DD/YYYY h:mm:ss A')
        };
      });

      dispatch({ type: 'SET_STATE', payload: { users, userLogs, isLoading: false } });
    });
  }, []);

  if (state.isLoading) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center' }}>
        <Spinner />
      </div>
    );
  }

  if (state.userLogs.length < 1) {
    return <div style={{ display: 'flex', justifyContent: 'center' }}>No user logs found.</div>;
  }

  return (
    <div>
      <h2>User Activity Logs</h2>
      <table className="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Action</th>
            <th>Source</th>
            <th>Location</th>
            <th>Map</th>
            <th>Details</th>
            <th>Timestamp</th>
          </tr>
        </thead>
        <tbody>
          {state.userLogs.map(({ id, name, type, source, location, details, createdAt }: any) => {
            return (
              <tr key={id}>
                <td>{name}</td>
                <td>{type}</td>
                <td>{source}</td>
                <td>{location}</td>
                <td>
                  {details.lat && details.long && (
                    <img
                      src={`https://maps.googleapis.com/maps/api/staticmap?center=${`${details.lat},${
                        details.long
                      }`}&zoom=15&size=300x200&maptype=roadmap&key=${googleMapsApiKey}`}
                    />
                  )}
                </td>
                <td>
                  <div>
                    {details.candidate && (
                      <div>
                        Candidate:{' '}
                        <a href={`/admin/candidates/update?id=${details.candidate.md5Hash}`} target="_blank">{`${
                          details.candidate.firstName
                        } ${details.candidate.lastName}`}</a>
                      </div>
                    )}
                    {details.testSession && (
                      <div>
                        Test Session:{' '}
                        <a href={`/admin/testsession/spreadsheet?id=${details.testSession.id}`} target="_blank">{`${
                          details.testSession.testSite.city
                        }, ${details.testSession.testSite.state} ${details.testSession.sessionNumber}`}</a>
                      </div>
                    )}
                    {details.checklistStepId && <div>{`Checklist Step: ${details.checklistStepId}`}</div>}
                    {details.lat && details.long && <div>{`Coordinates: ${details.lat}, ${details.long}`}</div>}
                    {details.geolocationErrorCode && <div>{`Geolocation Error: ${details.geolocationErrorCode}`}</div>}
                  </div>
                </td>
                <td>{createdAt}</td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
}

export default Main;
