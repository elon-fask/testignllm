import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { openDialog, focusCell, cancelFocusCell, blurCell } from '../../actionCreators';
import { cellTypes } from '../../lib/refs';
import ReadOnlyCell from './ReadOnlyCell';
import EditableCell from './EditableCell';
import DialogCell from './DialogCell';

class Cell extends Component {
  state = {
    isSelected: false,
    hasError: false
  };

  componentWillReceiveProps({ selectedRow, selectedCol, selectedTable, selectedHasError }) {
    const { row, col, table } = this.props;
    const isCellSelected = row === selectedRow && col === selectedCol && table === selectedTable;
    if (isCellSelected) {
      this.setState({
        isSelected: true
      });
      if (selectedHasError) {
        this.setState({
          hasError: true
        });
      }
    } else {
      this.setState({
        isSelected: false,
        hasError: false
      });
    }
  }

  clickHandler = () => {
    this.props.focusCell(this.props.row, this.props.col, this.props.table, this.props.candidateID);
  };

  blurHandler = event => {
    this.props.blurCell(this.props.candidateId, event.currentTarget.value, this.props.col);
  };

  cancelHandler = () => {
    this.props.cancelFocusCell();
  };

  render() {
    const commonProps = {
      className: this.props.className,
      contentClassName: this.props.contentClassName,
      value: this.props.value,
      clickHandler: this.clickHandler,
      cancelHandler: this.cancelHandler,
      blurHandler: this.blurHandler,
      isSelected: this.state.isSelected,
      hasError: this.state.hasError,
      style: this.props.style,
      width: this.props.width
    };

    switch (this.props.type) {
      case cellTypes.READONLY: {
        return <ReadOnlyCell {...commonProps} />;
      }
      case cellTypes.EDITABLE: {
        return <EditableCell {...commonProps} />;
      }
      case cellTypes.DIALOG: {
        return <DialogCell {...commonProps} />;
      }
      default: {
        return <EditableCell {...commonProps} />;
      }
    }
  }
}

const mapStateToProps = ({ ui: { selectedCell } }) => ({
  selectedRow: selectedCell.row,
  selectedCol: selectedCell.col,
  selectedTable: selectedCell.table,
  selectedHasError: selectedCell.selectedHasError
});
const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      openDialog,
      focusCell,
      cancelFocusCell,
      blurCell
    },
    dispatch
  );

export default connect(mapStateToProps, mapDispatchToProps)(Cell);
