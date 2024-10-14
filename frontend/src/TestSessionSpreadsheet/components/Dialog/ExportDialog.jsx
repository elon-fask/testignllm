import React, { Component } from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import CircularProgress from 'material-ui/CircularProgress';
import { getClassDates } from '../../../common/testSession';
import TextField from '../../../common/components/formik/TextField';

/* eslint-disable jsx-a11y/anchor-is-valid */
class ExportDialog extends Component {
  state = {
    isLoading: false
  };

  handleDownloadClassCertificates = () => {
    this.setState({ isLoading: true }, () => {
      this.props
        .downloadClassCertificates(this.props.values.instructorName, this.props.values.certDate)
        .then(({ data }) => {
          this.downloadLink.href = data.certsUrl;
          this.downloadLink.click();
          this.setState({ isLoading: false });
        })
        .catch(e => {
          this.setState({ isLoading: false });
        });
    });
  };

  render() {
    const actions = [
      <FlatButton label="Close" primary onClick={this.props.closeDialog} style={{ marginRight: '20px' }} />,
      <RaisedButton
        label="Download"
        primary
        disabled={this.state.isLoading}
        onClick={this.handleDownloadClassCertificates}
        style={{ marginRight: '20px' }}
      />,
      this.state.isLoading ? <CircularProgress /> : null
    ];

    return (
      <Dialog title={this.props.title} actions={actions} modal open={this.props.open}>
        <div>
          <form style={{ display: 'flex', flexDirection: 'column' }}>
            <Field name="instructorName" label="Instructor" component={TextField} />
            <Field name="certDate" label="Class Dates" component={TextField} />
          </form>
          <div style={{ display: 'none' }}>
            <a
              href="#"
              ref={downloadLink => {
                this.downloadLink = downloadLink;
              }}
              download
            >
              Placeholder Link
            </a>
          </div>
        </div>
      </Dialog>
    );
  }
}
/* eslint-enable jsx-a11y/anchor-is-valid */

export default withFormik({
  mapPropsToValues: props => ({
    instructorName: props.defaultInstructorName,
    certDate: getClassDates(props.startDate, props.endDate)
  }),
  validationSchema: Yup.object().shape({
    instructorName: Yup.string().required('Instructor name is required.'),
    certDate: Yup.string().required('Class dates is required.')
  })
})(ExportDialog);
