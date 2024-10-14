import React, { Component, Fragment } from 'react';
import Main from './Main';

class MainContainer extends Component {
  state = {
    testSessionUrl: null,
    newTab: false,
    iframeHeight: '100px'
  };

  generateReport = (url, newTab = false) => {
    this.setState({ testSessionUrl: url, newTab }, () => {
      if (newTab) {
        this.newTabAnchor.click();
      }
    });
  };

  resizeIframe = e => {
    const newHeight = `${e.currentTarget.contentWindow.document.body.scrollHeight}px`;
    this.setState({ iframeHeight: newHeight });
  };

  render() {
    return (
      <Fragment>
        <Main generateReport={this.generateReport} />
        {this.state.testSessionUrl &&
          !this.state.newTab && (
            <div style={{ width: '100%', height: '100%', marginTop: '10px' }}>
              <iframe
                src={this.state.testSessionUrl}
                title="Spreadsheet"
                style={{ height: this.state.iframeHeight, width: '100%' }}
                onLoad={this.resizeIframe}
                frameBorder={0}
                scrolling="no"
              />
            </div>
          )}
        <div style={{ display: 'none' }}>
          <a
            href={this.state.testSessionUrl}
            target="_blank"
            ref={newTabAnchor => {
              this.newTabAnchor = newTabAnchor;
            }}
          >
            View spreadsheet
          </a>
        </div>
      </Fragment>
    );
  }
}

export default MainContainer;
