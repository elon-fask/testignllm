import React, { Component, Fragment } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import IconButton from 'material-ui/IconButton';
import MoneyOff from 'material-ui/svg-icons/editor/money-off';
import Receipt from 'material-ui/svg-icons/action/receipt';
import Delete from 'material-ui/svg-icons/action/delete-forever';
import CircularProgress from 'material-ui/CircularProgress';
import {
  openDialog,
  deletePracticalTestSchedule,
  autoAdjustAccountBalance,
  toggleCollectPaymentOverride
} from '../../actionCreators';
import { dialogTypes, viewTypes } from '../../reducers/ui';
import { cellTypes } from '../../lib/refs';
import BaseCell from '../BaseCell';

class ActionsCell extends Component {
  state = {
    isLoading: false
  };

  getContent = () => {
    const bookkeepingActions = [viewTypes.BOOKKEEPING, viewTypes.BOOKKEEPING_BACKLOG];

    if (bookkeepingActions.includes(this.props.view)) {
      return (
        <Fragment>
          <IconButton
            tooltip="Mark as Invoiced - Collect Payment"
            tooltipPosition="top-center"
            onClick={this.handleCollectPaymentOverrideToggleClick}
          >
            <Receipt />
          </IconButton>
          <IconButton
            tooltip="Auto-adjust account balance to $0"
            tooltipPosition="top-center"
            onClick={this.handleAutoAdjustAccountBalanceClick}
          >
            <MoneyOff />
          </IconButton>
        </Fragment>
      );
    }

    if (this.props.view === viewTypes.PRACTICAL_TEST_SCHEDULE) {
      return (
        <Fragment>
          <IconButton
            tooltip="Delete"
            tooltipPosition="top-center"
            onClick={this.handleDeletePracticalTestScheduleClick}
          >
            <Delete />
          </IconButton>
        </Fragment>
      );
    }

    return null;
  };

  handleDeletePracticalTestScheduleClick = () => {
    const { candidate } = this.props;
    const { id: practicalTestScheduleId, day, date, time } = candidate.testSchedule;

    this.props.openDialog(dialogTypes.CONFIRM, {
      title: 'Delete Practical Test Schedule',
      body: `Delete Practical Test Schedule for ${candidate.name} (Day ${day} ${date} ${time})?`,
      confirm: () => {
        this.props.deletePracticalTestSchedule(practicalTestScheduleId);
      }
    });
  };

  handleCollectPaymentOverrideToggleClick = () => {
    this.setState({ isLoading: true }, () => {
      this.props
        .toggleCollectPaymentOverride(this.props.commonProps.candidateId)
        .then(() => {
          this.setState({ isLoading: false });
        })
        .catch(e => {
          this.setState({ isLoading: false });
        });
    });
  };

  handleAutoAdjustAccountBalanceClick = () => {
    this.setState({ isLoading: true }, () => {
      this.props
        .autoAdjustAccountBalance(this.props.commonProps.candidateId)
        .then(() => {
          this.setState({ isLoading: false });
        })
        .catch(e => {
          console.error(e);
          this.setState({ isLoading: false });
        });
    });
  };

  render() {
    return (
      <BaseCell
        key="actions"
        col={34}
        type={cellTypes.READONLY}
        className="no-print"
        value={this.state.isLoading ? <CircularProgress size={30} /> : this.getContent()}
        {...this.props.commonProps}
      />
    );
  }
}

const mapStateToProps = state => ({
  view: state.ui.view
});

const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      openDialog,
      autoAdjustAccountBalance,
      toggleCollectPaymentOverride,
      deletePracticalTestSchedule
    },
    dispatch
  );

export default connect(mapStateToProps, mapDispatchToProps)(ActionsCell);
