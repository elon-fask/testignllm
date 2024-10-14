import React, { Component } from 'react';

class ImageLoader extends Component {
  state = {
    isLoading: true
  };

  stopLoading = () => {
    this.setState({ isLoading: false });
  };

  render() {
    return (
      <div
        style={{
          width: '200px',
          height: '200px',
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          ...this.props.style
        }}
      >
        <i
          className="fa fa-circle-o-notch fa-spin"
          style={{ fontSize: '32px', display: this.state.isLoading ? 'inline-block' : 'none' }}
        />
        <img
          onLoad={this.stopLoading}
          src={this.props.src}
          alt={this.props.alt}
          className={`animated ${this.state.isLoading ? '' : 'fadeIn'}`}
          style={{
            maxWidth: '100%',
            maxHeight: '100%',
            display: this.state.isLoading ? 'none' : 'block'
          }}
        />
      </div>
    );
  }
}

export default ImageLoader;
