import React, { Component, Fragment } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import DropDownMenu from 'material-ui/DropDownMenu';
import MenuItem from 'material-ui/MenuItem';
import RaisedButton from 'material-ui/RaisedButton';
import CircularProgress from 'material-ui/CircularProgress';
import { dialogTypes, viewTypes } from '../reducers/ui';
import { openDialog, setView } from '../actionCreators';
import downloadApplicationFormsZip from '../actionCreators/reports/applicationFormsZip';
import downloadCandidatePhotosZip from '../actionCreators/reports/candidatePhotosZip';
import downloadClassCertificates from '../actionCreators/reports/classCertificates';
import downloadClassReadinessReport from '../actionCreators/reports/classReadinessReport';
import downloadWrittenClassReport from '../actionCreators/reports/writtenClassReport';
import downloadPracticalClassReport from '../actionCreators/reports/practicalClassReport';
import downloadPracticalTestScheduleReport from '../actionCreators/reports/practicalTestScheduleReport';
import downloadCombinedClassReport from '../actionCreators/reports/combinedClassReport';
import downloadEntireSpreadsheet from '../actionCreators/reports/entireSpreadsheet';
import downloadNLCSummaryReport from '../actionCreators/reports/nlcSummaryReport';
import ExportDialog from '../components/Dialog/ExportDialog';

class ExportMenu extends Component {
  state = {
    menuValue: 0,
    isLoading: false,
    isDialogOpen: false
  };

  get printerFriendlyHref() {
    const standardQuery = `id=${this.props.testSessionId}&printerFriendly=1&view=${this.props.view}`;

    const columnsQuery = this.props.columns.reduce((acc, col) => {
      return `${acc}&c[]=${col}`;
    }, '');

    const optionsQuery = Object.keys(this.props.options).reduce((acc, optionKey) => {
      return `${acc}&o[${optionKey}]=${this.props.options[optionKey]}`;
    }, '');

    return `/admin/testsession/spreadsheet?${standardQuery}${columnsQuery}${optionsQuery}`;
  }

  handleChange = (event, index, value) => {
    if (value === 5) {
      this.setState({
        menuValue: value,
        isDialogOpen: true
      });
    } else {
      this.setState({
        menuValue: value
      });
    }
  };

  handleChangeView = (event, index, value) => {
    if (value === viewTypes.CUSTOM) {
      this.props.openDialog();
    } else {
      this.props.setView(value);
    }
  };

  handleDownloadApplicationFormsZip = () => {
    this.setState(
      {
        isLoading: true
      },
      () => {
        this.props
          .downloadApplicationFormsZip()
          .then(({ data }) => {
            window.location.href = data.zipUrl;
            this.setState({ isLoading: false });
          })
          .catch(e => {
            this.setState({ isLoading: false });
          });
      }
    );
  };

  handleDownloadCandidatePhotosZip = () => {
    window.location.href = this.props.downloadCandidatePhotosZip();
  };

  handleDownloadDeclinedTestsZip = () => {
    window.location.href = `/admin/testsession/download-candidate-decline-attestations-zip?id=${
      this.props.testSessionId
    }`;
  };

  closeDialog = () => {
    this.setState({
      isDialogOpen: false
    });
  };

  renderDownloadButton = () => {
    const options = {
      1: this.props.downloadEntireSpreadsheet,
      2: this.props.downloadNLCSummaryReport,
      3: this.props.downloadClassReadinessReport,
      4: this.handleDownloadApplicationFormsZip,
      6: this.handleDownloadCandidatePhotosZip,
      8: this.props.downloadWrittenClassReport,
      9: this.props.downloadPracticalClassReport,
      10: this.props.downloadPracticalTestScheduleReport,
      11: this.props.downloadCombinedClassReport,
      12: this.handleDownloadDeclinedTestsZip
    };

    if (this.state.menuValue === 7) {
      return (
        <Fragment>
          <RaisedButton primary overlayStyle={{ paddingRight: '16px', paddingLeft: '16px' }}>
            <a
              style={{ color: 'white' }}
              href={`/admin/testsession/download-declined-test-attestations?id=${this.props.testSessionId}`}
              target="_blank"
            >
              PRINT REPORT
            </a>
          </RaisedButton>
        </Fragment>
      );
    }

    return options[this.state.menuValue] ? (
      <Fragment>
        <RaisedButton
          label="Download"
          primary
          disabled={this.state.isLoading}
          onTouchTap={options[this.state.menuValue]}
        />
        {this.state.isLoading && <CircularProgress />}
      </Fragment>
    ) : null;
  };

  render() {
    return (
      <Fragment>
        <div style={{ display: 'flex', justifyContent: 'space-between' }}>
          <div style={{ marginBottom: '10px', display: 'flex', alignItems: 'center' }}>
            <DropDownMenu value={this.state.menuValue} onChange={this.handleChange}>
              <MenuItem value={0} disabled primaryText="Export..." />
              <MenuItem value={1} primaryText="Entire Spreadsheet" />
              <MenuItem value={2} primaryText="NLC Summary Report" />
              <MenuItem value={3} primaryText="Class Readiness Roster" />
              <MenuItem value={8} primaryText="Written Class Report" />
              <MenuItem value={9} primaryText="Practical Exam Class Report" />
              <MenuItem value={10} primaryText="Practical Exam Only Candidates" />
              <MenuItem value={11} primaryText="Combined Class Report" />
              <MenuItem value={4} primaryText="Application Forms Zip" />
              <MenuItem value={5} primaryText="Class Certificates" />
              <MenuItem value={6} primaryText="Candidate Photos Zip" />
              <MenuItem value={7} primaryText="Declined Practical Exam Attestations" />
              <MenuItem value={12} primaryText="Declined Practical Exam Attestations (Zip)" />
            </DropDownMenu>
            {this.renderDownloadButton()}
          </div>
          <div style={{ display: 'flex', alignItems: 'center' }}>
            {this.props.view === viewTypes.PRACTICAL_TEST_SCHEDULE && (
              <div style={{ marginRight: '20px' }}>
                <RaisedButton
                  label="Add Practical Test Schedule"
                  primary
                  onTouchTap={this.props.openPracticalTestScheduleDialog}
                />
              </div>
            )}
            <div style={{ marginRight: '20px' }}>
              <RaisedButton label="Batch Grade Candidates" primary onTouchTap={this.props.openBatchGradeDialog} />
            </div>
            <div style={{ marginRight: '20px' }}>
              <RaisedButton
                label="Receive Payment from Company"
                primary
                onTouchTap={this.props.openCompanyPaymentDialog}
              />
            </div>
            <div style={{ padding: '8px 0 0 0' }}>Change View:</div>
            <DropDownMenu value={this.props.view} onChange={this.handleChangeView}>
              <MenuItem value={viewTypes.DEFAULT} primaryText="Default" />
              <MenuItem value={viewTypes.PRACTICAL_TEST_SCHEDULE} primaryText="Practical Test Schedule" />
              <MenuItem value={viewTypes.GRADING} primaryText="Grading (Pass/Fail Report)" />
              <MenuItem value={viewTypes.CLASSREADINESS} primaryText="Class Readiness Report" />
              <MenuItem value={viewTypes.CANDIDATE_CHECKLIST} primaryText="Candidate Checklist" />
              <MenuItem value={viewTypes.NOGRADES} primaryText="No Grades" />
              <MenuItem value={viewTypes.BOOKKEEPING} primaryText="Bookkeeping" />
              <MenuItem value={viewTypes.BOOKKEEPING_BACKLOG} primaryText="Bookkeeping (Reduced)" />
              <MenuItem value={viewTypes.APPFORMS} primaryText="Application Forms Only" />
              <MenuItem value={viewTypes.CUSTOM} primaryText="Custom" />
            </DropDownMenu>
            <div style={{ marginRight: '20px' }}>
              <RaisedButton label="Options" primary onTouchTap={this.props.openColumnOptionsDialog} />
            </div>
            <div>
              <RaisedButton primary overlayStyle={{ paddingRight: '16px', paddingLeft: '16px' }}>
                <a
                  href={this.printerFriendlyHref}
                  target={this.props.view === viewTypes.CUSTOM ? '_self' : '_blank'}
                  style={{ color: 'white' }}
                >
                  PRINTER FRIENDLY PAGE
                </a>
              </RaisedButton>
            </div>
          </div>
        </div>
        <ExportDialog
          open={this.state.isDialogOpen}
          title="Download Certificates"
          defaultInstructorName={this.props.testSession.instructor}
          startDate={this.props.testSession.startDate}
          endDate={this.props.testSession.endDate}
          downloadClassCertificates={this.props.downloadClassCertificates}
          closeDialog={this.closeDialog}
        />
      </Fragment>
    );
  }
}

const mapStateToProps = state => ({
  view: state.ui.view,
  columns: state.ui.columns,
  options: state.ui.options,
  testSession: {
    instructor: state.testSession.instructor,
    startDate: state.testSession.startDate,
    endDate: state.testSession.endDate
  }
});

const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      openBatchGradeDialog: () => openDialog(dialogTypes.BATCH_GRADE),
      openCompanyPaymentDialog: () => openDialog(dialogTypes.COMPANY_PAYMENT),
      openColumnOptionsDialog: () => openDialog(dialogTypes.COLUMN_OPTIONS),
      openPracticalTestScheduleDialog: () => openDialog(dialogTypes.PRACTICAL_TEST_SCHEDULE),
      setView,
      downloadEntireSpreadsheet,
      downloadNLCSummaryReport,
      downloadClassReadinessReport,
      downloadWrittenClassReport,
      downloadPracticalClassReport,
      downloadPracticalTestScheduleReport,
      downloadCombinedClassReport,
      downloadApplicationFormsZip,
      downloadClassCertificates,
      downloadCandidatePhotosZip
    },
    dispatch
  );

export default connect(mapStateToProps, mapDispatchToProps)(ExportMenu);
