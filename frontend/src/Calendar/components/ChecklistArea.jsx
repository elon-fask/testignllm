import React, { Component } from 'react';
import PropType from 'prop-types';
import FlatButton from 'material-ui/FlatButton';
import RaisedButton from 'material-ui/RaisedButton';
import Checkbox from 'material-ui/Checkbox';
import CircularProgress from 'material-ui/CircularProgress';
import _includes from 'lodash/includes';
import axios from 'axios';

export default class ChecklistArea extends Component {
  constructor(props) {
    super(props);

    const selectedTemplateIds = this.props.assignedChecklists.map(
      checklist => checklist.id
    );

    this.state = {
      isEditingAssignedChecklists: false,
      isSavingAssignedChecklists: false,
      selectedTemplateIds
    };
  }

  handleToggleEditAssignedChecklists = () => {
    const newState = !this.state.isEditingAssignedChecklists;
    this.setState({
      isEditingAssignedChecklists: newState
    });
  };

  handleToggleAssignedChecklist = (id, isChecked) => {
    let newState = [];

    if (isChecked) {
      newState = this.state.selectedTemplateIds.filter(
        templateId => id !== templateId
      );
    } else {
      newState = [...this.state.selectedTemplateIds, id];
    }

    this.setState({ selectedTemplateIds: newState });
  };

  handleSaveChecklistTemplateSelection = () => {
    this.setState({ isSavingAssignedChecklists: true });

    axios
      .post('/admin/testsession/assign-checklists', {
        'test-session-id': this.props.testSessionId,
        'checklist-ids': this.state.selectedTemplateIds,
        'checklist-type': this.props.type === 'Pre' ? 1 : 2
      })
      .then(response => {
        console.log(response);
        this.setState({ isSavingAssignedChecklists: false });
      })
      .catch(e => {
        console.log(e);
        this.setState({ isSavingAssignedChecklists: false });
      });
  };

  render() {
    const noAssignedChecklists = this.props.assignedChecklists.length < 1;
    const typeId = this.props.type === 'Pre' ? 1 : 2;
    const saveLabel = this.state.isSavingAssignedChecklists
      ? <span>
          Save <CircularProgress />
        </span>
      : <span>Save</span>;

    return (
      <div style={{ display: 'flex', flexDirection: 'column' }}>
        {noAssignedChecklists
          ? <span>No checklists are assigned.</span>
          : <div>
              <ul>
                {this.props.assignedChecklists.map(checklist =>
                  <li key={checklist.id}>
                    {checklist.name}
                  </li>
                )}
              </ul>
              <RaisedButton
                label="Fulfill Checklists"
                primary
                onTouchTap={() => {
                  window.location.href = `/admin/testsession/fulfill-checklists?id=${this
                    .props.testSessionId}&type=${typeId}`;
                }}
              />
            </div>}
        <FlatButton
          primary
          label={`Edit Assigned ${this.props.type} Checklists`}
          onTouchTap={this.handleToggleEditAssignedChecklists}
        />
        <FlatButton
          primary
          label={`View Saved ${this.props.type} Checklists`}
          onTouchTap={() => {
            window.location.href = `/admin/testsession/checklists?id=${this
              .props.testSessionId}&type=${typeId}`;
          }}
        />
        {this.state.isEditingAssignedChecklists &&
          <div>
            {this.props.checklistTemplates.map(checklistTemplate => {
              const isChecked = _includes(
                this.state.selectedTemplateIds,
                checklistTemplate.id
              );
              return (
                <Checkbox
                  key={checklistTemplate.id}
                  label={checklistTemplate.name}
                  checked={isChecked}
                  onCheck={() => {
                    this.handleToggleAssignedChecklist(
                      checklistTemplate.id,
                      isChecked
                    );
                  }}
                />
              );
            })}
            <RaisedButton
              label={saveLabel}
              primary
              onTouchTap={this.handleSaveChecklistTemplateSelection}
            />
          </div>}
      </div>
    );
  }
}

ChecklistArea.propTypes = {
  testSessionId: PropType.number.isRequired,
  type: PropType.string.isRequired,
  assignedChecklists: PropType.arrayOf(() => true).isRequired,
  checklistTemplates: PropType.arrayOf(
    PropType.shape({
      date_created: PropType.string.isRequired,
      id: PropType.number.isRequired,
      isArchived: PropType.number.isRequired,
      name: PropType.string.isRequired,
      type: PropType.number.isRequired
    })
  ).isRequired
};
