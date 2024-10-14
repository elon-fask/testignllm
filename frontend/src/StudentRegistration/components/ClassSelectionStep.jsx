import React, { Component } from 'react';
import FormContainer from './FormContainer';

class ClassSelectionStep extends Component {
  state = {};

  render() {
    return (
      <div>
        <h1 className="step-title">Choose Your Class Location</h1>
        <FormContainer title="Class Location">
          <div className="form-group">
            <label className="control-label" htmlFor="location" style={{ display: 'flex' }}>
              Select a Class Location
              <select id="location" className="form-control">
                <option value={undefined}>Please Choose</option>
                {this.props.testSites.map(testSite => (
                  <option key={testSite.id} value={testSite.id}>
                    {`${testSite.city}, ${testSite.state} - ${testSite.name}`}
                  </option>
                ))}
              </select>
            </label>
          </div>
        </FormContainer>
      </div>
    );
  }
}

export default ClassSelectionStep;
