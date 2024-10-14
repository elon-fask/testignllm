import axios from 'axios';
import React, { Component } from 'react';
import MUIDialog from 'material-ui/Dialog';
import FlatButton from 'material-ui/FlatButton';
import RaisedButton from 'material-ui/RaisedButton';
import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import { gradeValues } from '../../../common/grades';

const CommonRadioButtonOptions = [
  <RadioButton key="1" value="1" label={gradeValues['1']} />,
  <RadioButton key="2" value="0" label={gradeValues['0']} />,
  <RadioButton key="3" value="2" label={gradeValues['2']} />,
  <RadioButton key="4" value="3" label={gradeValues['3']} />,
  <RadioButton key="DISABLED" value="DISABLED" label="Disabled" />
];

const gradeKeyMapping = {
  gradeWrittenCore: 'W_EXAM_CORE',
  gradeWrittenSW: 'W_EXAM_TLL',
  gradeWrittenFX: 'W_EXAM_TSS',
  gradePracticalSW: 'P_TELESCOPIC_TLL',
  gradePracticalFX: 'P_TELESCOPIC_TSS'
};

class BatchGradeDialog extends Component {
  state = {
    gradeWrittenCore: '1',
    gradeWrittenSW: '1',
    gradeWrittenFX: '1',
    gradePracticalSW: 'DISABLED',
    gradePracticalFX: 'DISABLED'
  };

  handleSubmit = async () => {
    const grades = Object.keys(this.state).reduce((acc, key) => {
      if (this.state[key] === 'DISABLED') {
        return acc;
      }

      return {
        ...acc,
        [gradeKeyMapping[key]]: this.state[key]
      };
    }, {});

    try {
      await axios.post(`/admin/candidates/batch-update-grades-json`, {
        candidateIds: this.props.candidateIDs,
        grades
      });
      window.location.reload();
    } catch (e) {
      console.error(e);
    }
  };

  gradeWrittenOnly = () => {
    this.setState({
      gradeWrittenCore: '1',
      gradeWrittenSW: '1',
      gradeWrittenFX: '1',
      gradePracticalSW: 'DISABLED',
      gradePracticalFX: 'DISABLED'
    });
  };

  gradePracticalOnly = () => {
    this.setState({
      gradeWrittenCore: 'DISABLED',
      gradeWrittenSW: 'DISABLED',
      gradeWrittenFX: 'DISABLED',
      gradePracticalSW: '1',
      gradePracticalFX: '1'
    });
  };

  render() {
    const { props } = this;

    const actions = [
      <FlatButton label="Close" primary onTouchTap={props.closeDialog} style={{ marginRight: '24px' }} />,
      <RaisedButton label="Confirm" primary onTouchTap={this.handleSubmit} />
    ];

    return (
      <MUIDialog title="Batch Grade Candidates" actions={actions} modal open={props.isOpen} autoScrollBodyContent>
        <div style={{ marginBottom: '16px' }}>
          The following grades will be applied to all Candidates, if they are registered to take the corresponding test:
          <div>
            <RaisedButton
              label="Grade Written Only"
              primary
              onTouchTap={this.gradeWrittenOnly}
              style={{ marginRight: '24px' }}
            />
            <RaisedButton label="Grade Practical Only" primary onTouchTap={this.gradePracticalOnly} />
          </div>
        </div>
        <div>
          <div>
            <span style={{ fontWeight: 'bold' }}>Written Tests:</span>
          </div>
          <div>
            <div>
              Mobile Core Exam
              <RadioButtonGroup
                name="gradeWrittenCore"
                onChange={(e, value) => {
                  this.setState({ gradeWrittenCore: value });
                }}
                style={{ display: 'flex', marginBottom: '40px' }}
                valueSelected={this.state.gradeWrittenCore}
              >
                {CommonRadioButtonOptions}
              </RadioButtonGroup>
            </div>
            <div>
              Telescopic Boom-Swing Cab (TLL)
              <RadioButtonGroup
                name="gradeWrittenSW"
                onChange={(e, value) => {
                  this.setState({ gradeWrittenSW: value });
                }}
                style={{ display: 'flex', marginBottom: '40px' }}
                valueSelected={this.state.gradeWrittenSW}
              >
                {CommonRadioButtonOptions}
              </RadioButtonGroup>
            </div>
            <div>
              Telescopic Boom-Fixed Cab (TSS)
              <RadioButtonGroup
                name="gradeWrittenFX"
                onChange={(e, value) => {
                  this.setState({ gradeWrittenFX: value });
                }}
                style={{ display: 'flex', marginBottom: '40px' }}
                valueSelected={this.state.gradeWrittenFX}
              >
                {CommonRadioButtonOptions}
              </RadioButtonGroup>
            </div>
          </div>
          <div>
            <span style={{ fontWeight: 'bold' }}>Practical Exams:</span>
          </div>
          <div>
            <div>
              Telescopic Boom-Swing Cab (TLL)
              <RadioButtonGroup
                name="gradePracticalSW"
                onChange={(e, value) => {
                  this.setState({ gradePracticalSW: value });
                }}
                style={{ display: 'flex', marginBottom: '40px' }}
                valueSelected={this.state.gradePracticalSW}
              >
                {CommonRadioButtonOptions}
              </RadioButtonGroup>
            </div>
            <div>
              Telescopic Boom-Fixed Cab (TSS)
              <RadioButtonGroup
                name="gradePracticalFX"
                onChange={(e, value) => {
                  this.setState({ gradePracticalFX: value });
                }}
                style={{ display: 'flex', marginBottom: '40px' }}
                valueSelected={this.state.gradePracticalFX}
              >
                {CommonRadioButtonOptions}
              </RadioButtonGroup>
            </div>
          </div>
        </div>
      </MUIDialog>
    );
  }
}

export default BatchGradeDialog;
