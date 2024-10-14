import React, { Component, Fragment } from 'react';
import Dialog from './Dialog';

class CandidateManagementPanel extends Component {
  state = {
    certHref: '#'
  };

  downloadCert = (instructor, classDates) => {
    const { id } = this.props.candidate;

    this.setState(
      {
        certHref: `/admin/candidates/generate-certs?id=${id}&instructor=${instructor}&classDates=${classDates}`
      },
      () => {
        this.certDlBtn.click();
      }
    );
  };

  render() {
    const { props } = this;
    return (
      <Fragment>
        <button type="button" className="btn btn-primary" data-target={`#${props.modalId}`} data-toggle="modal">
          <i className="fa fa-cog" />&nbsp;Generate Certificate
        </button>
        <Dialog id={props.modalId} candidate={props.candidate} downloadCert={this.downloadCert} />
        <div style={{ display: 'none' }}>
          <a
            href={this.state.certHref}
            ref={certDlBtn => {
              this.certDlBtn = certDlBtn;
            }}
          >
            Download
          </a>
        </div>
      </Fragment>
    );
  }
}

export default CandidateManagementPanel;
