import React, { Component } from 'react';
import { connect } from 'react-redux';
import moment from 'moment';
import uuid from 'uuid/v1';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import SelectField from 'material-ui/SelectField';
import MenuItem from 'material-ui/MenuItem';
import Toggle from 'material-ui/Toggle';
import Dialog from 'material-ui/Dialog';
import CurrentClassSection from './CurrentClassSection';
import IncomingClassSection from './IncomingClassSection';
import { transactionTypes } from '../../common/candidateTransactions';
import { apiTransferCandidate } from '../../common/api';

const theme = getMuiTheme({
  palette: {
    accent1Color: '#0471af',
    primary1Color: '#0471af'
  }
});

class Main extends Component {
  constructor(props) {
    super(props);

    const presetState = {};

    if (props.preset.transferType === 'PARTIAL_PAID') {
      presetState.transferWrittenAndPractical = false;
      presetState.isRescheduleOnly = false;
    }

    if (props.preset.transferType === 'PARTIAL_UNPAID') {
      presetState.transferWrittenAndPractical = false;
      presetState.isRescheduleOnly = false;
      presetState.currentTransactions = [];
      presetState.incomingTransactions = props.transactions;
    }

    this.state = {
      currentTransactions: props.transactions,
      currentTransactionsDiff: {
        create: [],
        update: [],
        delete: []
      },
      incomingTransactions: [],
      incomingApplicationTypeId: null,
      confirmDeleteDialogOpen: false,
      confirmDelete: {
        transactionId: null,
        current: false
      },
      transactionEditorOpen: false,
      transactionEditor: {
        id: null,
        current: false,
        action: 'Edit',
        typeId: 10,
        amount: 0,
        remarks: ''
      },
      remarks: '',
      isRescheduleOnly: props.defaults.isRescheduleOnly,
      transferWrittenAndPractical: props.preset.bothTestSessions,
      isOverridingNcccoFeesCurrent: false,
      ncccoFeesOverrideCurrent: {
        written: '',
        practical: ''
      },
      isOverridingNcccoFeesIncoming: false,
      ncccoFeesOverrideIncoming: {
        written: '',
        practical: ''
      },
      ...presetState
    };
  }

  addTransaction = (typeId, amount, current = false) => {
    const newTransaction = {
      id: uuid(),
      date: moment().format('M/D/YYYY'),
      typeId,
      amount,
      remarks: this.state.transactionEditor.remarks || null
    };

    const transactions = current ? this.state.currentTransactions : this.state.incomingTransactions;

    transactions.push(newTransaction);

    const statePayload = current
      ? {
          currentTransactions: transactions,
          currentTransactionsDiff: {
            ...this.state.currentTransactionsDiff,
            create: [...this.state.currentTransactionsDiff.create, newTransaction.id]
          }
        }
      : { incomingTransactions: transactions };

    this.setState(statePayload);
  };

  handleToggleRescheduleOnly = () => {
    this.setState({
      isRescheduleOnly: !this.state.isRescheduleOnly
    });
  };

  handleToggleTransferWrittenAndPractical = () => {
    this.setState({
      transferWrittenAndPractical: !this.state.transferWrittenAndPractical
    });
  };

  handleEditIncomingKeyword = searchText => {
    if (this.props.applicationTypeKeywords.includes(searchText)) {
      const applicationType = this.props.applicationTypes.find(appType => appType.keyword === searchText);

      const payload = {};

      if (applicationType.name === 'Test') {
        payload.ncccoFeesOverrideIncoming = {
          written: 0,
          practical: 0
        };
        payload.isOverridingNcccoFeesIncoming = true;
      } else {
        payload.ncccoFeesOverrideIncoming = {
          written: '',
          practical: ''
        };
        payload.isOverridingNcccoFeesIncoming = false;
      }

      const hasWritten = Object.keys(applicationType.applicationFormsMerged).reduce((acc, key) => {
        if (key.slice(0, 2) === 'W_' && applicationType.applicationFormsMerged[key] === 'on') {
          return true;
        }
        return acc;
      }, false);

      const hasPractical = Object.keys(applicationType.applicationFormsMerged).reduce((acc, key) => {
        if (key.slice(0, 2) === 'P_' && applicationType.applicationFormsMerged[key] === 'on') {
          return true;
        }
        return acc;
      }, false);

      payload.incomingApplicationTypeId = this.props.applicationTypes.find(a => a.keyword === searchText).id;

      if (hasWritten && hasPractical) {
        payload.transferWrittenAndPractical = true;
      } else {
        payload.transferWrittenAndPractical = false;
      }

      this.setState(payload);
    } else {
      this.setState({
        incomingApplicationTypeId: null
      });
    }
  };

  handleToggleOverrideNcccoFees = current => {
    if (current) {
      this.setState({
        isOverridingNcccoFeesCurrent: !this.state.isOverridingNcccoFeesCurrent
      });
    } else {
      this.setState({
        isOverridingNcccoFeesIncoming: !this.state.isOverridingNcccoFeesIncoming
      });
    }
  };

  handleEditOverrideNcccoFees = (current, type, value) => {
    if (current) {
      this.setState({
        ncccoFeesOverrideCurrent: {
          ...this.state.ncccoFeesOverrideCurrent,
          [type]: value
        }
      });
    } else {
      this.setState({
        ncccoFeesOverrideIncoming: {
          ...this.state.ncccoFeesOverrideIncoming,
          [type]: value
        }
      });
    }
  };

  handleClickDelete = (transactionId, current) => {
    this.setState({
      confirmDeleteDialogOpen: true,
      confirmDelete: {
        transactionId,
        current
      }
    });
  };

  handleClickCancelDelete = () => {
    this.setState({
      confirmDeleteDialogOpen: false,
      confirmDelete: {
        transactionId: null,
        current: false
      }
    });
  };

  handleClickConfirmDelete = () => {
    const transactions = this.state.confirmDelete.current
      ? this.state.currentTransactions
      : this.state.incomingTransactions;
    const newTransactions = transactions.filter(
      transaction => transaction.id !== this.state.confirmDelete.transactionId
    );

    if (this.state.confirmDelete.current) {
      const { currentTransactionsDiff: newCurrentTransactionsDiff } = this.state;
      if (this.state.currentTransactionsDiff.create.includes(this.state.confirmDelete.transactionId)) {
        newCurrentTransactionsDiff.create = this.state.currentTransactionsDiff.create.filter(
          transactionId => transactionId !== this.state.confirmDelete.transactionId
        );
      }

      if (this.props.transactions.find(transaction => transaction.id === this.state.confirmDelete.transactionId)) {
        newCurrentTransactionsDiff.delete = [
          ...this.state.currentTransactionsDiff.delete,
          this.state.confirmDelete.transactionId
        ];
      }

      this.setState({
        confirmDeleteDialogOpen: false,
        currentTransactions: newTransactions,
        currentTransactionsDiff: newCurrentTransactionsDiff
      });
    } else {
      this.setState({
        confirmDeleteDialogOpen: false,
        incomingTransactions: newTransactions
      });
    }
  };

  handleClickNewTransaction = (current = false) => {
    this.setState({
      transactionEditorOpen: true,
      transactionEditor: {
        action: 'Add',
        current,
        typeId: 10,
        amount: 0,
        remarks: ''
      }
    });
  };

  handleChangeTransactionType = (e, k, value) => {
    this.setState({
      transactionEditor: {
        ...this.state.transactionEditor,
        typeId: value
      }
    });
  };

  handleChangeAmount = (e, value) => {
    this.setState({
      transactionEditor: {
        ...this.state.transactionEditor,
        amount: value
      }
    });
  };

  handleChangeRetestCraneSelection = (e, k, value) => {
    this.setState({
      transactionEditor: {
        ...this.state.transactionEditor,
        retestCraneSelection: value
      }
    });
  };

  handleChangeRemarks = (e, value) => {
    this.setState({
      transactionEditor: {
        ...this.state.transactionEditor,
        remarks: value
      }
    });
  };

  handleClickAddTransaction = () => {
    const newTransaction = {
      id: uuid(),
      date: moment().format('M/D/YYYY'),
      typeId: parseInt(this.state.transactionEditor.typeId, 10),
      amount: parseFloat(this.state.transactionEditor.amount),
      remarks: this.state.transactionEditor.remarks || null
    };

    if (newTransaction.typeId === 50) {
      newTransaction.retestCraneSelection = this.state.transactionEditor.retestCraneSelection;
    }

    const statePayload = {
      transactionEditorOpen: false,
      transactionEditor: {
        action: 'Add',
        typeId: 10,
        amount: 0,
        remarks: ''
      }
    };

    const transactions = this.state.transactionEditor.current
      ? this.state.currentTransactions
      : this.state.incomingTransactions;

    transactions.push(newTransaction);

    if (this.state.transactionEditor.current) {
      statePayload.currentTransactions = transactions;
      statePayload.currentTransactionsDiff = {
        ...this.state.currentTransactionsDiff,
        create: [...this.state.currentTransactionsDiff.create, newTransaction.id]
      };
    } else {
      statePayload.incomingTransactions = transactions;
    }

    this.setState(statePayload);
  };

  handleClickCancelTransaction = () => {
    this.setState({
      transactionEditorOpen: false,
      transactionEditor: {
        action: 'Add',
        typeId: 1,
        amount: 0,
        remarks: 'Testing'
      }
    });
  };

  handleClickCancelMain = () => {
    window.location.reload();
  };

  handleSubmitTransfer = () => {
    const { transactions: oldTransactions } = this.props;

    const {
      isRescheduleOnly,
      incomingApplicationTypeId,
      ncccoFeesOverrideCurrent,
      ncccoFeesOverrideIncoming,
      currentTransactions,
      currentTransactionsDiff,
      incomingTransactions
    } = this.state;

    const ncccoFeesOverrideCurrentObj = {};
    if (ncccoFeesOverrideCurrent.written !== '') {
      ncccoFeesOverrideCurrentObj.written = parseFloat(ncccoFeesOverrideCurrent.written);
    }
    if (ncccoFeesOverrideCurrent.practical !== '') {
      ncccoFeesOverrideCurrentObj.practical = parseFloat(ncccoFeesOverrideCurrent.practical);
    }

    const ncccoFeesOverrideIncomingObj = {};
    if (ncccoFeesOverrideIncoming.written !== '') {
      ncccoFeesOverrideIncomingObj.written = parseFloat(ncccoFeesOverrideIncoming.written);
    }
    if (ncccoFeesOverrideIncoming.practical !== '') {
      ncccoFeesOverrideIncomingObj.practical = parseFloat(ncccoFeesOverrideIncoming.practical);
    }

    const options = {};

    if (incomingApplicationTypeId) {
      options.incomingApplicationTypeId = incomingApplicationTypeId;
    }

    if (Object.keys(ncccoFeesOverrideCurrentObj).length > 0) {
      options.ncccoFeesOverrideCurrent = ncccoFeesOverrideCurrentObj;
    }

    if (Object.keys(ncccoFeesOverrideIncomingObj).length > 0) {
      options.ncccoFeesOverrideIncoming = ncccoFeesOverrideIncomingObj;
    }

    if (
      currentTransactionsDiff.create.length > 0 ||
      currentTransactionsDiff.update.length > 0 ||
      currentTransactionsDiff.delete.length > 0
    ) {
      options.currentTransactionsDiff = {
        create: [],
        update: [],
        delete: []
      };
    }

    if (currentTransactionsDiff.create.length > 0) {
      options.currentTransactionsDiff.create = currentTransactionsDiff.create.map(transactionId =>
        currentTransactions.find(transaction => transaction.id === transactionId)
      );
    }

    if (currentTransactionsDiff.update.length > 0) {
      options.currentTransactionsDiff.update = currentTransactionsDiff.update.map(transactionId =>
        currentTransactions.find(transaction => transaction.id === transactionId)
      );
    }

    if (currentTransactionsDiff.delete.length > 0) {
      options.currentTransactionsDiff.delete = currentTransactionsDiff.delete;
    }

    if (incomingTransactions.length > 0) {
      options.incomingTransactions = incomingTransactions;
    }

    apiTransferCandidate(
      isRescheduleOnly,
      this.state.transferWrittenAndPractical,
      this.props.candidateId,
      this.props.incomingTestSession.id,
      this.state.remarks,
      options
    ).then(({ data }) => {
      console.log(data);
      window.location.href = data.nextUrl;
    });
  };

  render() {
    const { props, state } = this;

    const currentApplicationType = props.applicationTypes[props.applicationTypeId];

    return (
      <MuiThemeProvider muiTheme={theme}>
        <div>
          <div>
            <h4>Name: {props.name}</h4>
            <div style={{ display: 'flex' }}>
              <div style={{ width: '180px', marginRight: '40px' }}>
                <Toggle
                  label="Reschedule Only"
                  toggled={this.state.isRescheduleOnly}
                  onToggle={this.handleToggleRescheduleOnly}
                />
              </div>
              <div style={{ width: '320px' }}>
                <Toggle
                  label="Transfer Written and Practical Classes"
                  toggled={this.state.transferWrittenAndPractical}
                  onToggle={this.handleToggleTransferWrittenAndPractical}
                />
              </div>
            </div>
          </div>
          <div style={{ display: 'flex' }}>
            {props.currentTestSession && (
              <CurrentClassSection
                testSession={props.currentTestSession}
                testSessionCounterpart={props.currentTestSessionCounterpart}
                transferWrittenAndPractical={this.state.transferWrittenAndPractical}
                applicationType={currentApplicationType}
                transactions={state.currentTransactions}
                transactionTypes={transactionTypes}
                handleClickNewTransaction={() => {
                  this.handleClickNewTransaction(true);
                }}
                handleClickDeleteTransaction={id => {
                  this.handleClickDelete(id, true);
                }}
                isRescheduleOnly={state.isRescheduleOnly}
                isOverridingNcccoFees={state.isOverridingNcccoFeesCurrent}
                ncccoFeesOverride={state.ncccoFeesOverrideCurrent}
                handleToggleOverrideNcccoFees={() => {
                  this.handleToggleOverrideNcccoFees(true);
                }}
                handleEditOverrideNcccoFees={(type, value) => {
                  this.handleEditOverrideNcccoFees(true, type, value);
                }}
              />
            )}
            <IncomingClassSection
              testSession={props.incomingTestSession}
              testSessionCounterpart={props.incomingTestSessionCounterpart}
              applicationTypeId={state.incomingApplicationTypeId}
              applicationTypes={props.applicationTypes}
              transactions={state.incomingTransactions}
              transactionTypes={transactionTypes}
              applicationTypeKeywords={props.applicationTypeKeywords}
              transferWrittenAndPractical={this.state.transferWrittenAndPractical}
              handleToggleTransferWrittenAndPractical={this.handleToggleTransferWrittenAndPractical}
              handleEditIncomingKeyword={this.handleEditIncomingKeyword}
              handleClickNewTransaction={() => {
                this.handleClickNewTransaction(false);
              }}
              handleClickDeleteTransaction={id => {
                this.handleClickDelete(id, false);
              }}
              addTransaction={(typeId, amount) => {
                this.addTransaction(typeId, amount, false);
              }}
              isRescheduleOnly={state.isRescheduleOnly}
              isOverridingNcccoFees={state.isOverridingNcccoFeesIncoming}
              ncccoFeesOverride={state.ncccoFeesOverrideIncoming}
              handleToggleOverrideNcccoFees={() => {
                this.handleToggleOverrideNcccoFees(false);
              }}
              handleEditOverrideNcccoFees={(type, value) => {
                this.handleEditOverrideNcccoFees(false, type, value);
              }}
            />
          </div>
          {state.transactionEditorOpen && (
            <div style={{ marginTop: '20px', display: 'flex', flexDirection: 'column' }}>
              <h4>{`${state.transactionEditor.action} Transaction`}</h4>
              <SelectField
                floatingLabelText="Type"
                floatingLabelFixed
                value={state.transactionEditor.typeId.toString()}
                onChange={this.handleChangeTransactionType}
              >
                {Object.keys(transactionTypes).map(typeId => (
                  <MenuItem key={typeId} value={typeId} primaryText={transactionTypes[typeId]} />
                ))}
              </SelectField>
              <TextField
                type="number"
                value={state.transactionEditor.amount}
                onChange={this.handleChangeAmount}
                floatingLabelText="Amount"
                floatingLabelFixed
              />
              {parseInt(state.transactionEditor.typeId, 10) === 50 && (
                <SelectField
                  floatingLabelText="Crane Selection"
                  floatingLabelFixed
                  value={state.transactionEditor.retestCraneSelection}
                  onChange={this.handleChangeRetestCraneSelection}
                >
                  <MenuItem value="fx" primaryText="Fixed Cab" />
                  <MenuItem value="sw" primaryText="Swing Cab" />
                  <MenuItem value="both" primaryText="Both" />
                </SelectField>
              )}
              <TextField
                value={state.transactionEditor.remarks}
                onChange={this.handleChangeRemarks}
                floatingLabelText="Remarks"
                floatingLabelFixed
                multiLine
                rows={2}
                rowsMax={4}
              />
              <div style={{ marginTop: '10px', display: 'flex' }}>
                <RaisedButton
                  label="Cancel"
                  backgroundColor="#d9534f"
                  labelStyle={{ color: '#fff' }}
                  onClick={this.handleClickCancelTransaction}
                />
                <RaisedButton
                  primary
                  label="Add"
                  style={{ marginLeft: '20px' }}
                  onClick={this.handleClickAddTransaction}
                />
              </div>
            </div>
          )}
          <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
            <TextField
              value={state.remarks}
              onChange={(e, value) => {
                this.setState({ remarks: value });
              }}
              floatingLabelText="Notes"
              floatingLabelFixed
              multiLine
              rows={2}
              rowsMax={4}
            />
          </div>
          <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
            <RaisedButton
              label="Cancel"
              backgroundColor="#d9534f"
              labelStyle={{ color: '#fff' }}
              onClick={this.handleClickCancelMain}
              style={{ marginRight: '20px' }}
            />
            <RaisedButton
              label="OK"
              primary
              labelStyle={{ color: '#fff' }}
              onClick={this.handleSubmitTransfer}
              disabled={!state.isRescheduleOnly && state.incomingApplicationTypeId == null}
            />
          </div>
          <Dialog
            title="Confirm Delete Transaction"
            actions={[
              <RaisedButton
                label="Cancel"
                backgroundColor="#d9534f"
                labelStyle={{ color: '#fff' }}
                onClick={this.handleClickCancelDelete}
              />,
              <RaisedButton
                primary
                label="Confirm Delete"
                style={{ marginLeft: '20px' }}
                onClick={this.handleClickConfirmDelete}
              />
            ]}
            modal
            open={this.state.confirmDeleteDialogOpen}
          />
        </div>
      </MuiThemeProvider>
    );
  }
}

const mapStateToProps = state => {
  const transactions = state.candidate.transactions.map(transaction => {
    const type = transaction.chargeType
      ? transactionTypes[transaction.chargeType]
      : transactionTypes[transaction.paymentType];

    return {
      id: transaction.id,
      amount: transaction.amount,
      date: moment(transaction.date_created).format('M/D/YYYY'),
      typeId: transaction.chargeType || transaction.paymentType,
      remarks: transaction.remarks
    };
  });

  const applicationTypeKeywords = state.applicationTypes.map(({ keyword }) => keyword);

  return {
    candidateId: state.candidate.id,
    name: state.candidate.name,
    applicationTypeId: state.candidate.applicationTypeId,
    transactions,
    applicationTypes: state.applicationTypes,
    applicationTypeKeywords,
    currentTestSession: state.currentTestSession || null,
    currentTestSessionCounterpart: state.currentTestSessionCounterpart || null,
    incomingTestSession: state.incomingTestSession,
    incomingTestSessionCounterpart: state.incomingTestSessionCounterpart || null,
    defaults: state.defaults,
    preset: state.preset
  };
};

export default connect(mapStateToProps)(Main);
