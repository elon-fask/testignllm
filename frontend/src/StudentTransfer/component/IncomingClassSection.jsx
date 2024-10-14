import React from 'react';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import AutoComplete from 'material-ui/AutoComplete';
import Toggle from 'material-ui/Toggle';
import TextField from 'material-ui/TextField';
import ApplicationTypeTestTable from '../../common/components/ApplicationTypeTestTable';
import { summarizeTransactions } from '../../common/candidateTransactions';

const IncomingClassSection = props => {
  const applicationType = props.applicationTypes.find(({ id }) => id === props.applicationTypeId) || {
    price: 0,
    applicationForms: []
  };

  const classTitle =
    props.transferWrittenAndPractical && props.testSessionCounterpart ? (
      <h4>
        Current Classes:<br />
        {props.testSession.name}
        <br />
        {props.testSessionCounterpart.name}
      </h4>
    ) : (
      <h4>{`Current Class: ${props.testSession.name}`}</h4>
    );

  const { formSetup, totalFees } = applicationType;
  const transactionSummary = summarizeTransactions(props.transactions);

  return (
    <div style={{ margin: '10px', border: '2px solid rgb(158, 158, 158)', padding: '10px' }}>
      {classTitle}
      {!props.isRescheduleOnly && (
        <div>
          <div style={{ marginBottom: '20px' }}>
            <h4>Application Details</h4>
            <AutoComplete
              floatingLabelText="Application Type"
              floatingLabelFixed
              dataSource={props.applicationTypeKeywords}
              onUpdateInput={props.handleEditIncomingKeyword}
            />
            {props.applicationTypeId && (
              <div style={{ marginBottom: '20px' }}>
                <div>Selected Tests</div>
                <ApplicationTypeTestTable formSetup={formSetup} />
                <div>
                  <div style={{ width: '320px' }}>
                    <Toggle
                      label="Transfer Written and Practical Classes"
                      toggled={props.transferWrittenAndPractical}
                      onToggle={props.handleToggleTransferWrittenAndPractical}
                    />
                  </div>
                  <div>
                    NCCCO Fees: <span style={{ fontWeight: 'bold' }}>${totalFees}</span>{' '}
                    <FlatButton
                      label="Add Transaction"
                      primary
                      disabled={totalFees <= 0}
                      onClick={() => {
                        props.addTransaction(70, totalFees);
                      }}
                    />
                    <div style={{ maxWidth: '200px' }}>
                      <Toggle
                        label="Override NCCCO Fees"
                        toggled={props.isOverridingNcccoFees}
                        onToggle={props.handleToggleOverrideNcccoFees}
                      />
                    </div>
                    {props.isOverridingNcccoFees && (
                      <div>
                        <TextField
                          value={props.ncccoFeesOverride.written}
                          type="number"
                          floatingLabelText="Written Fees"
                          floatingLabelFixed
                          style={{ marginRight: '20px', maxWidth: '160px' }}
                          onChange={(event, value) => {
                            props.handleEditOverrideNcccoFees('written', value);
                          }}
                        />
                        <TextField
                          value={props.ncccoFeesOverride.practical}
                          type="number"
                          floatingLabelText="Practical Fees"
                          floatingLabelFixed
                          style={{ marginRight: '20px', maxWidth: '160px' }}
                          onChange={(event, value) => {
                            props.handleEditOverrideNcccoFees('practical', value);
                          }}
                        />
                      </div>
                    )}
                  </div>
                  <div>
                    Application Type Price: <span style={{ fontWeight: 'bold' }}>${applicationType.price}</span>{' '}
                    <FlatButton
                      label="Add Transaction"
                      primary
                      disabled={applicationType.price <= 0}
                      onClick={() => {
                        props.addTransaction(70, applicationType.price);
                      }}
                    />
                  </div>
                </div>
              </div>
            )}
          </div>
          <div>
            <h4>Account Balance</h4>
            {props.transactions.length > 0 && (
              <div>
                <div>
                  Price: <span style={{ fontWeight: 'bold' }}>${transactionSummary.customerCharges}</span>
                </div>
                <div>
                  Remaining Amount: <span style={{ fontWeight: 'bold' }}>${transactionSummary.amountDue}</span>
                </div>
              </div>
            )}
            <Table selectable={false}>
              <TableHeader adjustForCheckbox={false} displaySelectAll={false}>
                <TableRow>
                  <TableHeaderColumn>Date</TableHeaderColumn>
                  <TableHeaderColumn>Type</TableHeaderColumn>
                  <TableHeaderColumn>Amount</TableHeaderColumn>
                  <TableHeaderColumn>Remarks</TableHeaderColumn>
                  <TableHeaderColumn>Actions</TableHeaderColumn>
                </TableRow>
              </TableHeader>
              <TableBody displayRowCheckbox={false}>
                {props.transactions.map(transaction => (
                  <TableRow key={transaction.id}>
                    <TableRowColumn>{transaction.date}</TableRowColumn>
                    <TableRowColumn>{props.transactionTypes[transaction.typeId]}</TableRowColumn>
                    <TableRowColumn>${transaction.amount}</TableRowColumn>
                    <TableRowColumn>{transaction.remarks}</TableRowColumn>
                    <TableRowColumn>
                      <RaisedButton
                        onClick={() => {
                          props.handleClickDeleteTransaction(transaction.id);
                        }}
                        label={<i className="fa fa-trash" aria-hidden />}
                        backgroundColor="#d9534f"
                        labelStyle={{ color: '#fff' }}
                        style={{ minWidth: 'auto', width: 'auto' }}
                      />
                    </TableRowColumn>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
            <RaisedButton
              primary
              label="New Transaction"
              style={{ marginTop: '10px' }}
              onClick={props.handleClickNewTransaction}
            />
          </div>
        </div>
      )}
    </div>
  );
};

export default IncomingClassSection;
