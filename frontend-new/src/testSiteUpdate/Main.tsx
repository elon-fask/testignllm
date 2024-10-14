import * as React from 'react';
import ApolloClient from 'apollo-boost';
import gql from 'graphql-tag';
import Spinner from '../common/components/Spinner';

const { useEffect, useState, Fragment } = React;

declare const testSiteId: string;
declare const apiUrl: string;
declare const googleMapsApiKey: string;

const client = new ApolloClient({
  uri: apiUrl
});

const query = gql`
  {
    testSite(id: ${testSiteId}) {
      id
      locationCoordinatesFromAddress
    }
  }
`;

function Main() {
  const [location, setLocation] = useState('');

  useEffect(() => {
    client.query({ query }).then(({ data }: { data: any }) => {
      setLocation(data.testSite.locationCoordinatesFromAddress);
    });
  }, []);

  return (
    <div className="form-group">
      <div className="col-xs-4 control-label">Location (Google Maps)</div>
      <div className="col-xs-12 col-md-5">
        {location ? (
          <Fragment>
            <div style={{ marginBottom: '8px' }}>
              <img
                src={`https://maps.googleapis.com/maps/api/staticmap?center=${location}&zoom=15&size=300x200&maptype=roadmap&key=${googleMapsApiKey}`}
              />
            </div>
            <div>
              <a
                className="btn btn-primary"
                href={`https://www.google.com/maps/search/?api=1&query=${location}`}
                target="_blank"
              >
                View in Google Maps
              </a>
            </div>
          </Fragment>
        ) : (
          <Spinner />
        )}
      </div>
    </div>
  );
}

export default Main;
