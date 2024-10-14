import React, { Component } from 'react';

export default class TextField extends Component {
  state = {
    value: this.props.initialValue || ''
  };

  componentDidMount() {
    this.input.focus();
    this.input.setSelectionRange(0, this.state.value.length);
  }

  componentWillReceiveProps({ hasError }) {
    if (hasError) {
      this.input.focus();
      this.input.setSelectionRange(this.state.value.length);
    }
  }

  handleTextChange = e => {
    this.setState({
      value: e.currentTarget.value
    });
  };

  handleKeyPress = e => {
    if (e.keyCode === 27) {
      this.props.cancelHandler();
    }

    if (e.key === 'Enter') {
      this.props.blurHandler({
        currentTarget: {
          value: this.state.value
        }
      });
    }
  };

  bindInputRef = input => {
    this.input = input;
  };

  render() {
    return (
      <input
        type="text"
        style={{ width: '100%' }}
        value={this.state.value}
        onChange={this.handleTextChange}
        ref={this.bindInputRef}
        onBlur={this.props.blurHandler}
        onKeyDown={this.handleKeyPress}
      />
    );
  }
}
