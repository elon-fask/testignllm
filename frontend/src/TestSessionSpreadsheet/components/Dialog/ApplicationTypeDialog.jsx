import React, { Component } from 'react';
import MUIDialog from 'material-ui/Dialog';
import FlatButton from 'material-ui/FlatButton';
import RaisedButton from 'material-ui/RaisedButton';
import SelectField from 'material-ui/SelectField';
import MenuItem from 'material-ui/MenuItem';
import ApplicationTypeTestTable from '../../../common/components/ApplicationTypeTestTable';

export default class ApplicationTypeDialog extends Component {
  state = {
    selectedApplicationTypeID: this.props.data.initialApplicationTypeID
  };

  handleSelect = (e, k, value) => {
    this.setState({
      selectedApplicationTypeID: value
    });
  };

  handleConfirm = () => {
    this.props.blurCell(this.props.data.candidateID, this.state.selectedApplicationTypeID, 2);
  };

  render() {
    const actions = [
      <FlatButton label="Cancel" primary onTouchTap={this.props.closeDialog} style={{ marginRight: '10px' }} />,
      <RaisedButton label="OK" primary onTouchTap={this.handleConfirm} />
    ];

    const selectedApplication = this.props.applicationTypes[this.state.selectedApplicationTypeID];

    return (
      <MUIDialog
        title={`Select Application Type - ${this.props.candidateName}`}
        actions={actions}
        modal
        open={this.props.isOpen}
      >
        <SelectField
          style={{ width: '100%' }}
          value={this.state.selectedApplicationTypeID}
          onChange={this.handleSelect}
        >
          {this.props.applicationTypeIDs.map(applicationTypeID => {
            const applicationType = this.props.applicationTypes[applicationTypeID];
            return (
              <MenuItem key={applicationType.id} value={applicationType.id} primaryText={applicationType.displayName} />
            );
          })}
        </SelectField>
        <ApplicationTypeTestTable formSetup={selectedApplication.formSetup} />
      </MUIDialog>
    );
  }
}
