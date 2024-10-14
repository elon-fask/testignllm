import React from 'react';
import ClassSelectionStep from './ClassSelectionStep';

const Stepper = () => (
  <div className="steps">
    <div className="step step-current">1</div>
    <div className="step">2</div>
    <div className="step">3</div>
    <div className="step">4</div>
    <div className="step">5</div>
    <div className="steps-line" />
  </div>
);

const Registration = props => (
  <div className="center-content-flex">
    <Stepper />
    <ClassSelectionStep testSites={props.testSites} />
  </div>
);

export default Registration;
