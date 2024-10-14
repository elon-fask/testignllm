import React, { Component } from 'react';
import PropTypes from 'prop-types';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import Paper from 'material-ui/Paper';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import SelectField from 'material-ui/SelectField';
import MenuItem from 'material-ui/MenuItem';
import TextField from 'material-ui/TextField';
import Checkbox from 'material-ui/Checkbox';
import CircularProgress from 'material-ui/CircularProgress';
import Dialog from 'material-ui/Dialog';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { updateFormValue, uploadCandidateInfo, closeUploadWaitPrompt, closeModal } from '../actionCreators';
import FieldWrapper from '../../common/components/FieldWrapper';
import PreviewTable from './PreviewTable';

const theme = getMuiTheme({
  palette: {
    primary1Color: '#0471af',
    accent1Color: '#0471af'
  }
});

FieldWrapper.propTypes = {
  label: PropTypes.string.isRequired,
  children: PropTypes.node.isRequired
};

class BulkRegistrationComponent extends Component {
  handleFileSelect = e => {
    this.props.updateFormValue('selectedFileName', e.currentTarget.files[0].name, {
      file: e.target.files[0]
    });
  };

  handleRefreshPreview = () => {
    this.props.updateFormValue('selectedFileName', this.fileInput.files[0].name, {
      file: this.fileInput.files[0]
    });
  };

  render() {
    const {
      updateFormValue,
      applicationTypes,
      applicationTypeIds,
      promoCodes,
      promoCodeIds,
      testSites,
      testSiteIds,
      testSessions,
      testSessionIds,
      ui: { formValues, errors, table, tableErrors, modal }
    } = this.props;

    return (
      <MuiThemeProvider muiTheme={theme}>
        <div>
          <div style={{ display: 'flex', marginBottom: '20px' }}>
            <Paper style={{ padding: '20px', flexBasis: '46%', marginRight: '20px' }}>
              <p>Please choose a Microsoft Excel file to upload:</p>
              <div style={{ display: 'flex', alignItems: 'center' }}>
                <RaisedButton label="Choose File" containerElement="label" primary>
                  <input
                    type="file"
                    style={{ display: 'none' }}
                    onChange={this.handleFileSelect}
                    id="file-input"
                    ref={fileInput => {
                      this.fileInput = fileInput;
                    }}
                  />
                </RaisedButton>
                <span style={{ display: 'flex', flexDirection: 'column' }}>
                  <span style={{ marginLeft: '10px' }}>{formValues.selectedFileName}</span>
                  {errors.selectedFileName && (
                    <span
                      style={{
                        marginLeft: '10px',
                        fontSize: '12px',
                        color: 'rgb(244, 67, 54)'
                      }}
                    >
                      {errors.selectedFileName}
                    </span>
                  )}
                </span>
              </div>
              <div>
                <FieldWrapper label="Customize column mappings" style={{ marginTop: '10px' }}>
                  <Checkbox
                    checked={formValues.customizeColumnMappings}
                    style={{ width: 'auto' }}
                    label=""
                    onCheck={(e, checked) => {
                      updateFormValue('customizeColumnMappings', checked);
                    }}
                  />
                </FieldWrapper>
                <FieldWrapper label="Application Type">
                  <SelectField
                    floatingLabelText=""
                    errorText={errors.selectedApplicationTypeId}
                    value={formValues.selectedApplicationTypeId}
                    style={{ width: '600px' }}
                    onChange={(e, key, value) => {
                      updateFormValue('selectedApplicationTypeId', value);
                    }}
                  >
                    <MenuItem value={null} primaryText="" />
                    {applicationTypeIds.map(applicationTypeId => {
                      const { keyword, price, description } = applicationTypes[applicationTypeId];
                      return (
                        <MenuItem
                          key={applicationTypeId}
                          value={applicationTypeId}
                          primaryText={`${keyword} ($${price}) ${description}`}
                        />
                      );
                    })}
                  </SelectField>
                </FieldWrapper>
                <FieldWrapper label="Promo Code">
                  <SelectField
                    floatingLabelText=""
                    errorText={errors.selectedPromoCodeId}
                    value={formValues.selectedPromoCodeId}
                    style={{ width: '600px' }}
                    onChange={(e, key, value) => {
                      updateFormValue('selectedPromoCodeId', value);
                    }}
                  >
                    <MenuItem value={null} primaryText="" />
                    {promoCodeIds.map(promoCodeId => {
                      const { code, discount, assignedToName } = promoCodes[promoCodeId];
                      return (
                        <MenuItem
                          key={promoCodeId}
                          value={promoCodeId}
                          primaryText={`${code} ($${discount}) ${assignedToName}`}
                        />
                      );
                    })}
                  </SelectField>
                </FieldWrapper>
                <FieldWrapper label="PO Number">
                  <TextField
                    name="poNumber"
                    value={formValues.poNumber}
                    errorText={errors.poNumber}
                    onChange={(e, newValue) => {
                      updateFormValue('poNumber', newValue);
                    }}
                  />
                </FieldWrapper>
                <FieldWrapper label="Company">
                  <TextField
                    name="company"
                    value={formValues.company}
                    errorText={errors.company}
                    onChange={(e, newValue) => {
                      updateFormValue('company', newValue);
                    }}
                  />
                </FieldWrapper>
                <FieldWrapper label="Test Site">
                  <SelectField
                    floatingLabelText=""
                    errorText={errors.selectedTestSiteId}
                    value={formValues.selectedTestSiteId}
                    style={{ width: '600px' }}
                    onChange={(e, key, value) => {
                      updateFormValue('selectedTestSiteId', value);
                    }}
                  >
                    <MenuItem value={null} primaryText="" />
                    {testSiteIds.map(testSiteId => {
                      const { name, city, state } = testSites[testSiteId];
                      return (
                        <MenuItem key={testSiteId} value={testSiteId} primaryText={`${name} - ${city}, ${state}`} />
                      );
                    })}
                  </SelectField>
                </FieldWrapper>
                <FieldWrapper label="Test Session">
                  <SelectField
                    floatingLabelText=""
                    errorText={errors.selectedTestSessionId}
                    value={formValues.selectedTestSessionId}
                    style={{ width: '600px' }}
                    onChange={(e, key, value) => {
                      updateFormValue('selectedTestSessionId', value);
                    }}
                  >
                    <MenuItem value={null} primaryText="" />
                    {testSessionIds.map(testSessionId => {
                      const { sessionNumber, testSiteId, startDate, endDate } = testSessions[testSessionId];

                      if (testSiteId === formValues.selectedTestSiteId) {
                        return (
                          <MenuItem
                            key={testSessionId}
                            value={testSessionId}
                            primaryText={`${startDate} - ${endDate}: Session No. ${sessionNumber}`}
                          />
                        );
                      }
                      return null;
                    })}
                  </SelectField>
                </FieldWrapper>
                <FieldWrapper label="Starting Row">
                  <TextField
                    name="startingRow"
                    value={formValues.startingRowValue}
                    errorText={errors.startingRowValue}
                    onChange={(e, newValue) => {
                      updateFormValue('startingRowValue', newValue);
                    }}
                  />
                </FieldWrapper>
                <FieldWrapper label="Ending Row">
                  <TextField
                    name="endingRow"
                    errorText={errors.endingRowValue}
                    value={formValues.endingRowValue}
                    onChange={(e, newValue) => {
                      updateFormValue('endingRowValue', newValue);
                    }}
                  />
                </FieldWrapper>
                <FieldWrapper label="Send email notifications to Students">
                  <Checkbox
                    checked={formValues.emailStudentsChecked}
                    style={{ width: 'auto' }}
                    label=""
                    onCheck={(e, checked) => {
                      updateFormValue('emailStudentsChecked', checked);
                    }}
                  />
                </FieldWrapper>
                <br />
                <RaisedButton
                  onTouchTap={this.props.uploadCandidateInfo}
                  label="Proceed with Upload"
                  labelColor="#fff"
                  backgroundColor="#5cb85c"
                  disabled={tableErrors.length > 0}
                />
              </div>
            </Paper>
            <Paper
              style={{
                padding: '20px',
                flexBasis: '46%',
                display: formValues.customizeColumnMappings ? 'block' : 'none'
              }}
            >
              <span style={{ fontWeight: 'bold' }}>Column Mappings</span>
              <FieldWrapper label="Last Name">
                <TextField
                  name="columnLastName"
                  value={formValues.columnLastName}
                  errorText={errors.columnLastName}
                  onChange={(e, newValue) => {
                    updateFormValue('columnLastName', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="First Name">
                <TextField
                  name="columnFirstName"
                  value={formValues.columnFirstName}
                  errorText={errors.columnFirstName}
                  onChange={(e, newValue) => {
                    updateFormValue('columnFirstName', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="Email">
                <TextField
                  name="columnEmail"
                  value={formValues.columnEmail}
                  errorText={errors.columnEmail}
                  onChange={(e, newValue) => {
                    updateFormValue('columnEmail', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="Phone">
                <TextField
                  name="columnPhone"
                  value={formValues.columnPhone}
                  errorText={errors.columnPhone}
                  onChange={(e, newValue) => {
                    updateFormValue('columnPhone', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="Birthday">
                <TextField
                  name="columnBirthday"
                  value={formValues.columnBirthday}
                  errorText={errors.columnBirthday}
                  onChange={(e, newValue) => {
                    updateFormValue('columnBirthday', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="Address">
                <TextField
                  name="columnAddress"
                  value={formValues.columnAddress}
                  errorText={errors.columnAddress}
                  onChange={(e, newValue) => {
                    updateFormValue('columnAddress', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="City">
                <TextField
                  name="columnCity"
                  value={formValues.columnCity}
                  errorText={errors.columnCity}
                  onChange={(e, newValue) => {
                    updateFormValue('columnCity', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="State">
                <TextField
                  name="columnState"
                  value={formValues.columnState}
                  errorText={errors.columnState}
                  onChange={(e, newValue) => {
                    updateFormValue('columnState', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="Zip">
                <TextField
                  name="columnZip"
                  value={formValues.columnZip}
                  errorText={errors.columnZip}
                  onChange={(e, newValue) => {
                    updateFormValue('columnZip', newValue);
                  }}
                />
              </FieldWrapper>
              <FieldWrapper label="Company">
                <TextField
                  name="columnCompany"
                  value={formValues.columnCompany}
                  errorText={errors.columnCompany}
                  onChange={(e, newValue) => {
                    updateFormValue('columnCompany', newValue);
                  }}
                />
              </FieldWrapper>
              <div>
                Note: Please click on the Reload File button if you wish to read the currently selected file again, with
                the new column mappings.
              </div>
              <RaisedButton label="Reload File" onClick={this.handleRefreshPreview} primary />
            </Paper>
          </div>
          {!table.isLoadingTablePreview &&
            table.tablePreview && (
              <Paper>
                <PreviewTable highestColumn={table.highestColumn} table={table.tablePreview} />
              </Paper>
            )}
          {table.isLoadingTablePreview && <CircularProgress />}
          <Dialog
            title="Uploading Student Info and Preparing Zip File"
            actions={[<FlatButton label="Close" primary onTouchTap={this.props.closeUploadWaitPrompt} />]}
            modal
            open={table.isUploadingDialogVisible}
          >
            Please wait while the zip file is being prepared...{' '}
            {table.isUploadingFinished ? 'Done!' : <CircularProgress />}
            <br />
            {table.isUploadingFinished && (
              <a href={table.zipUrl}>
                <RaisedButton label="Download .zip File" primary />
              </a>
            )}
          </Dialog>
          <Dialog
            title="Please correct the following errors and try uploading the Excel file again"
            actions={[<FlatButton label="Close" primary onTouchTap={this.props.closeModal} />]}
            modal
            open={modal.isVisible}
          >
            <ul>
              {tableErrors.map(
                (errorMsg, i) => (
                  /* eslint-disable react/no-array-index-key */
                  <li key={i}>{errorMsg}</li>
                )
                /* eslint-enable react/no-array-index-key */
              )}
            </ul>
          </Dialog>
        </div>
      </MuiThemeProvider>
    );
  }
}

BulkRegistrationComponent.propTypes = {
  updateFormValue: PropTypes.func.isRequired,
  uploadCandidateInfo: PropTypes.func.isRequired,
  closeUploadWaitPrompt: PropTypes.func.isRequired,
  closeModal: PropTypes.func.isRequired,
  applicationTypes: PropTypes.objectOf(() => true).isRequired,
  applicationTypeIds: PropTypes.arrayOf(PropTypes.string).isRequired,
  promoCodes: PropTypes.objectOf(() => true).isRequired,
  promoCodeIds: PropTypes.arrayOf(PropTypes.string).isRequired,
  testSites: PropTypes.objectOf(() => true).isRequired,
  testSiteIds: PropTypes.arrayOf(PropTypes.string).isRequired,
  testSessions: PropTypes.objectOf(() => true).isRequired,
  testSessionIds: PropTypes.arrayOf(PropTypes.string).isRequired,
  ui: PropTypes.objectOf(() => true).isRequired
};

const mapStateToProps = state => state;

const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      updateFormValue,
      uploadCandidateInfo,
      closeUploadWaitPrompt,
      closeModal
    },
    dispatch
  );

const BulkRegistrationContainer = connect(mapStateToProps, mapDispatchToProps)(BulkRegistrationComponent);

export default BulkRegistrationContainer;
