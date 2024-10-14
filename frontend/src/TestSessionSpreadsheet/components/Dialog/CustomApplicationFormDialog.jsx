import _union from 'lodash/union';
import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import Dialog from 'material-ui/Dialog';
import CertFees from './CertFees';
import RecertFees from './RecertFees';
import MUIDialog from 'material-ui/Dialog';
import Toggle from 'material-ui/Toggle';
import FlatButton from 'material-ui/FlatButton';
import RaisedButton from 'material-ui/RaisedButton';
import { createTransaction, deleteTransaction } from '../../actionCreators';
import { getFeeTotal } from '../../lib/helpers';

/* eslint-disable react/prefer-stateless-function */
class CustomApplicationFormDialog extends Component {
  constructor(props) {
    super(props);

    const { candidate } = this.props.data;
    const applicationType = this.props.applicationTypes[candidate.applicationTypeID];
    const formSetup = { ...applicationType.formSetup, ...candidate.customFormSetup };
    const checkedFees =
      candidate.customCheckedFees.length > 0 ? candidate.customCheckedFees : applicationType.checkedFees;

    this.state = {
      formSetup,
      checkedFees,
      isDialogOpen: false,
      dialogType: 'default',
      dialogTitle: 'Error',
      dialogMessage: ''
    };
  }

  getOtherFees = () => {
    let total = 0;
    if (this.state.formSetup.lateFeeEnabled) {
      total += 50;
    }
    if (this.state.formSetup.incompleteFeeEnabled) {
      total += 30;
    }

    return total;
  };

  enableFormSetupField = fieldName => {
    this.setState({
      formSetup: {
        ...this.state.formSetup,
        [fieldName]: true
      }
    });
  };

  disableFormSetupField = fieldName => {
    this.setState({
      formSetup: {
        ...this.state.formSetup,
        [fieldName]: false
      }
    });
  };

  createLateFeeTransaction = () => {
    this.props.createTransaction(this.props.data.candidate.id, {
      paymentType: 10,
      chargeType: 74
    });

    this.addCheckedFee('W_FEE_LATE');
    this.enableFormSetupField('lateFeeEnabled');
    this.handleCloseNestedDialog();
  };

  deleteLateFeeTransaction = () => {
    this.props.data.candidate.transactions.map(({ id, paymentType, chargeType }) => {
      if (paymentType === 10 && chargeType === 74) {
        this.props.deleteTransaction(this.props.data.candidate.id, id);
      }
    });

    this.removeCheckedFee('W_FEE_LATE');
    this.disableFormSetupField('lateFeeEnabled');
    this.handleCloseNestedDialog();
  };

  createIncompleteFeeTransaction = () => {
    this.props.createTransaction(this.props.data.candidate.id, {
      paymentType: 10,
      chargeType: 72
    });

    this.addCheckedFee('W_FEE_INCOMPLETE');
    this.enableFormSetupField('incompleteFeeEnabled');
    this.handleCloseNestedDialog();
  };

  deleteIncompleteFeeTransaction = () => {
    this.props.data.candidate.transactions.map(({ id, paymentType, chargeType }) => {
      if (paymentType === 10 && chargeType === 72) {
        this.props.deleteTransaction(this.props.data.candidate.id, id);
      }
    });

    this.removeCheckedFee('W_FEE_INCOMPLETE');
    this.disableFormSetupField('incompleteFeeEnabled');
    this.handleCloseNestedDialog();
  };

  preCheckEdit = (fieldName, isAdding) => {
    const isRemoving = !isAdding;

    if (fieldName === 'W_FEE_LATE') {
      const lateCharges = this.props.data.candidate.transactions.reduce(
        (acc, { paymentType, chargeType }) => (paymentType === 10 && chargeType === 74 ? acc + 1 : acc),
        0
      );

      if (lateCharges === 0 && isAdding) {
        this.setState({
          isDialogOpen: true,
          dialogType: 'addLateFee'
        });
        return;
      }

      if (lateCharges > 0 && isRemoving) {
        this.setState({
          isDialogOpen: true,
          dialogType: 'removeLateFee'
        });
        return;
      }

      if (isAdding) {
        this.addCheckedFee('W_FEE_LATE');
        this.enableFormSetupField('lateFeeEnabled');
      }

      if (isRemoving) {
        this.removeCheckedFee('W_FEE_LATE');
        this.disableFormSetupField('lateFeeEnabled');
      }
    }

    if (fieldName === 'W_FEE_INCOMPLETE') {
      const incompleteCharges = this.props.data.candidate.transactions.reduce(
        (acc, { paymentType, chargeType }) => (paymentType === 10 && chargeType === 72 ? acc + 1 : acc),
        0
      );

      if (incompleteCharges === 0 && isAdding) {
        this.setState({
          isDialogOpen: true,
          dialogType: 'addIncompleteFee'
        });
        return;
      }

      if (incompleteCharges > 0 && isRemoving) {
        this.setState({
          isDialogOpen: true,
          dialogType: 'removeIncompleteFee'
        });
        return;
      }

      if (isAdding) {
        this.addCheckedFee('W_FEE_INCOMPLETE');
        this.enableFormSetupField('incompleteFeeEnabled');
      }

      if (isRemoving) {
        this.removeCheckedFee('W_FEE_INCOMPLETE');
        this.disableFormSetupField('incompleteFeeEnabled');
      }
    }
  };

  addCheckedFee = fieldName => {
    this.setState({
      checkedFees: _union(this.state.checkedFees, [fieldName])
    });
  };

  removeCheckedFee = fieldName => {
    this.setState({
      checkedFees: this.state.checkedFees.filter(checkedFee => checkedFee !== fieldName)
    });
  };

  handleSubmit = () => {
    const { formSetup, checkedFees } = this.state;
    this.props.blurCell(this.props.data.candidate.id, { formSetup, checkedFees }, 3);
  };

  handleCloseNestedDialog = () => {
    this.setState({
      isDialogOpen: false,
      dialogType: 'default'
    });
  };

  render() {
    const { candidate } = this.props.data;
    const applicationType = this.props.applicationTypes[candidate.applicationTypeID];
    const { formSetup, checkedFees } = this.state;

    const actions = [
      <FlatButton label="Cancel" primary onTouchTap={this.props.closeDialog} style={{ marginRight: '10px' }} />,
      <RaisedButton label="OK" primary onTouchTap={this.handleSubmit} />
    ];

    const dialogTypes = {
      addLateFee: {
        title: 'Add Late Fee',
        body:
          'No Late Fee charges found for this Candidate. Would you like to add a Late Fee charge to Candidate transaction history?',
        actions: [
          <FlatButton
            label="Cancel"
            primary
            onTouchTap={this.handleCloseNestedDialog}
            style={{ marginRight: '10px' }}
          />,
          <RaisedButton label="OK" primary onTouchTap={this.createLateFeeTransaction} />
        ]
      },
      removeLateFee: {
        title: 'Remove Late Fee',
        body:
          'A Late Fee charge transaction was found for this Candidate. Would you like to delete the Late Fee charge in the Candidate transaction history?',
        actions: [
          <FlatButton
            label="Cancel"
            primary
            onTouchTap={this.handleCloseNestedDialog}
            style={{ marginRight: '10px' }}
          />,
          <RaisedButton label="OK" primary onTouchTap={this.deleteLateFeeTransaction} />
        ]
      },
      addIncompleteFee: {
        title: 'Add Incomplete/Change Application Fee',
        body:
          'No Incomplete/Change Application Fee charges found for this Candidate. Would you like to add a Incomplete/Change Application Fee charge to Candidate transaction history?',
        actions: [
          <FlatButton
            label="Cancel"
            primary
            onTouchTap={this.handleCloseNestedDialog}
            style={{ marginRight: '10px' }}
          />,
          <RaisedButton label="OK" primary onTouchTap={this.createIncompleteFeeTransaction} />
        ]
      },
      removeIncompleteFee: {
        title: 'Add Incomplete/Change Application Fee',
        body:
          'A Incomplete/Change Application Fee transaction was found for this Candidate. Would you like to delete the Incomplete/Change Application Fee charge in the Candidate transaction history?',
        actions: [
          <FlatButton
            label="Cancel"
            primary
            onTouchTap={this.handleCloseNestedDialog}
            style={{ marginRight: '10px' }}
          />,
          <RaisedButton label="OK" primary onTouchTap={this.deleteIncompleteFeeTransaction} />
        ]
      },
      default: {
        title: 'Error',
        body: '',
        actions: []
      }
    };

    return (
      <MUIDialog
        title={`Edit Application Form - ${candidate.name}`}
        actions={actions}
        modal
        autoScrollBodyContent
        open={this.props.isOpen}
      >
        <a href={`/admin/candidates/update?id=${candidate.idHash}`}>Go to Candidate Application Page</a>
        <div style={{ display: 'flex', marginTop: '20px' }}>
          <div style={{ flexBasis: '160px', marginRight: '40px' }}>
            <span style={{ fontWeight: 'bold' }}>Written</span>
            <Toggle
              toggled={formSetup.coreEnabled}
              onToggle={(e, isChecked) => {
                if (isChecked) {
                  this.enableFormSetupField('coreEnabled');
                } else {
                  this.disableFormSetupField('coreEnabled');
                }
              }}
              labelStyle={{ fontWeight: 'normal' }}
              label="Core Exam"
            />
            <Toggle
              toggled={formSetup.writtenSWEnabled}
              onToggle={(e, isChecked) => {
                if (isChecked) {
                  this.enableFormSetupField('writtenSWEnabled');
                } else {
                  this.disableFormSetupField('writtenSWEnabled');
                }
              }}
              labelStyle={{ fontWeight: 'normal' }}
              label="Written SW"
            />
            <Toggle
              toggled={formSetup.writtenFXEnabled}
              onToggle={(e, isChecked) => {
                if (isChecked) {
                  this.enableFormSetupField('writtenFXEnabled');
                } else {
                  this.disableFormSetupField('writtenFXEnabled');
                }
              }}
              labelStyle={{ fontWeight: 'normal' }}
              label="Written FX"
            />
          </div>
          <div style={{ flexBasis: '160px' }}>
            <span style={{ fontWeight: 'bold' }}>Practical</span>
            <Toggle
              toggled={formSetup.practicalSWEnabled}
              onToggle={(e, isChecked) => {
                if (isChecked) {
                  this.enableFormSetupField('practicalSWEnabled');
                } else {
                  this.disableFormSetupField('practicalSWEnabled');
                }
              }}
              labelStyle={{ fontWeight: 'normal' }}
              label="Practical SW"
            />
            <Toggle
              toggled={formSetup.practicalFXEnabled}
              onToggle={(e, isChecked) => {
                if (isChecked) {
                  this.enableFormSetupField('practicalFXEnabled');
                } else {
                  this.disableFormSetupField('practicalFXEnabled');
                }
              }}
              labelStyle={{ fontWeight: 'normal' }}
              label="Practical FX"
            />
          </div>
        </div>
        {applicationType.isRecert ? (
          <RecertFees
            preCheckEdit={this.preCheckEdit}
            addCheckedFee={this.addCheckedFee}
            removeCheckedFee={this.removeCheckedFee}
            checkedFees={checkedFees}
          />
        ) : (
          <CertFees
            preCheckEdit={this.preCheckEdit}
            addCheckedFee={this.addCheckedFee}
            removeCheckedFee={this.removeCheckedFee}
            checkedFees={checkedFees}
          />
        )}
        <Dialog
          title={dialogTypes[this.state.dialogType].title}
          modal={false}
          open={this.state.isDialogOpen}
          actions={dialogTypes[this.state.dialogType].actions}
        >
          {dialogTypes[this.state.dialogType].body}
        </Dialog>
      </MUIDialog>
    );
  }
}

const mapDispatchToProps = dispatch =>
  bindActionCreators(
    {
      createTransaction,
      deleteTransaction
    },
    dispatch
  );

export default connect(null, mapDispatchToProps)(CustomApplicationFormDialog);
