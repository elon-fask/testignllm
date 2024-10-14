import axios from 'axios';
import { formatMoney } from 'accounting';
import React, { Component } from 'react';
import MUIDialog from 'material-ui/Dialog';
import FlatButton from 'material-ui/FlatButton';
import RaisedButton from 'material-ui/RaisedButton';
import MUICheckbox from 'material-ui/Checkbox';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import {
  getIterResultAllCandidateSelected,
  getIterResultTotalAmountDuesPaid,
  getIterResultCandidateSelectionSchema,
  getIterResultCandidatePaymentSchema
} from './helpers';
import FieldWrapper from '../../../../common/components/FieldWrapper';
import TextField from '../../../../common/components/formik/TextField';
import AutoComplete from '../../../../common/components/formik/AutoComplete';
import OrderedSelectField from '../../../../common/components/formik/OrderedSelectField';
import Checkbox from '../../../../common/components/formik/Checkbox';
import { companyPaymentTxTypes, companyTxTypesStr, getAmountValues } from '../../../../common/companyTransactions';

const paymentTypeOptions = companyPaymentTxTypes.map(type => ({
  key: type,
  value: type,
  text: companyTxTypesStr[type]
}));

const fieldWrapperLabelStyle = { width: '300px' };

class CompanyPaymentDialog extends Component {
  handleSelectAllClick = (e, isChecked) => {
    const buildAllSelectState = state =>
      this.props.candidateIDs.reduce(
        (acc, id) => ({
          ...acc,
          [id]: state
        }),
        {}
      );

    this.props.setFieldValue('candidateSelection', isChecked ? buildAllSelectState(true) : buildAllSelectState(false));
  };

  render() {
    const { props } = this;

    const actions = [
      <FlatButton label="Close" primary onTouchTap={props.closeDialog} style={{ marginRight: '24px' }} />,
      <RaisedButton label="Confirm" primary onTouchTap={props.handleSubmit} />
    ];

    const { amountReceived, candidateSelection, applyPercentageAdjustment, percentageAdjustment } = props.values;

    const { allCandidatesSelected, totalAmountDuesPaid } = props.candidateIDs.reduce(
      (acc, id) => ({
        allCandidatesSelected: getIterResultAllCandidateSelected(acc.allCandidatesSelected, id, candidateSelection),
        totalAmountDuesPaid: getIterResultTotalAmountDuesPaid(acc.totalAmountDuesPaid, id, props.values)
      }),
      {
        allCandidatesSelected: true,
        totalAmountDuesPaid: 0
      }
    );

    const { maximumAmountCoverage, shouldShowmaximumAmountCoverage, amountLeft } = getAmountValues(
      amountReceived,
      totalAmountDuesPaid,
      applyPercentageAdjustment && percentageAdjustment
    );

    return (
      <MUIDialog
        title="Receive Payment from Company"
        actions={actions}
        modal
        open={props.isOpen}
        autoScrollBodyContent
        contentStyle={{ width: '50%', maxWidth: 'none' }}
      >
        <div style={{ paddingTop: '20px' }}>
          <form onSubmit={props.handleSubmit}>
            <FieldWrapper label="Company" labelStyle={fieldWrapperLabelStyle}>
              <Field
                name="company"
                component={AutoComplete}
                dataSource={props.companies}
                options={{
                  dataSourceConfig: { text: 'name', value: 'id' },
                  onNewRequest: (chosenRequest, index) => {
                    if (index > -1) {
                      this.props.setFieldValue('companyId', chosenRequest.id);
                    }
                  }
                }}
              />
            </FieldWrapper>
            <FieldWrapper label="Payment Type" labelStyle={fieldWrapperLabelStyle}>
              <Field name="type" component={OrderedSelectField} options={paymentTypeOptions} />
            </FieldWrapper>
            {props.values.type === 'PAYMENT_CHECK' && (
              <FieldWrapper label="Check Number" labelStyle={fieldWrapperLabelStyle}>
                <Field name="checkNumber" component={TextField} />
              </FieldWrapper>
            )}
            <FieldWrapper label="Amount Received" labelStyle={fieldWrapperLabelStyle}>
              <Field name="amountReceived" type="number" component={TextField} />
            </FieldWrapper>
            <FieldWrapper label="Apply a Percentage Adjustment" labelStyle={fieldWrapperLabelStyle}>
              <Field name="applyPercentageAdjustment" component={Checkbox} style={{ width: 'auto' }} />
            </FieldWrapper>
            {props.values.applyPercentageAdjustment && (
              <FieldWrapper label="Percentage Adjustment (%)" labelStyle={fieldWrapperLabelStyle}>
                <Field name="percentageAdjustment" type="number" component={TextField} />
              </FieldWrapper>
            )}
            <div style={{ marginTop: '24px' }}>
              <h4>Company Transaction Summary</h4>
              <div style={{ display: 'flex' }}>
                <div style={{ marginRight: '8px' }}>
                  <div>Total Candidate Amount Dues Paid:</div>
                  {shouldShowmaximumAmountCoverage && (
                    <div>{`Maximum Amount that Payment Can Cover (+${percentageAdjustment}%):`}</div>
                  )}
                  <div>Amount Left to be Distributed:</div>
                </div>
                <div>
                  <div>{formatMoney(totalAmountDuesPaid)}</div>
                  {shouldShowmaximumAmountCoverage && <div>{formatMoney(maximumAmountCoverage)}</div>}
                  <div>{formatMoney(amountLeft)}</div>
                </div>
              </div>
            </div>
            <div style={{ marginTop: '24px' }}>
              <h4>Candidate Transactions</h4>
              <div>
                <Table selectable={false}>
                  <TableHeader displaySelectAll={false} enableSelectAll={false} adjustForCheckbox={false}>
                    <TableRow>
                      <TableHeaderColumn style={{ width: '50px' }}>
                        <div>
                          <MUICheckbox onCheck={this.handleSelectAllClick} checked={allCandidatesSelected} />
                        </div>
                      </TableHeaderColumn>
                      <TableHeaderColumn>Name</TableHeaderColumn>
                      <TableHeaderColumn>Company</TableHeaderColumn>
                      <TableHeaderColumn>Amount Paid For by Company</TableHeaderColumn>
                      <TableHeaderColumn>Amount Due</TableHeaderColumn>
                      <TableHeaderColumn>Payment Status (Preview)</TableHeaderColumn>
                    </TableRow>
                  </TableHeader>
                  <TableBody displayRowCheckbox={false}>
                    {props.candidateIDs.map(id => {
                      const candidate = props.candidates[id];
                      const style = {};
                      if (candidateSelection[id]) {
                        style.backgroundColor = 'rgb(224, 224, 224)';
                      }

                      return (
                        <TableRow key={id} style={style}>
                          <TableRowColumn style={{ width: '50px' }}>
                            <div>
                              <Field name={`candidateSelection.${id}`} component={Checkbox} />
                            </div>
                          </TableRowColumn>
                          <TableRowColumn>{candidate.name}</TableRowColumn>
                          <TableRowColumn>{candidate.company}</TableRowColumn>
                          <TableRowColumn>
                            <div style={{ position: 'relative' }}>
                              <Field type="number" name={`payment-${id}`} />
                              <div
                                style={{
                                  position: 'absolute',
                                  fontSize: '0.8em',
                                  color: 'rgb(244, 67, 54)'
                                }}
                              >
                                {props.errors[`payment-${id}`]}
                              </div>
                            </div>
                          </TableRowColumn>
                          <TableRowColumn>{formatMoney(candidate.amountDue)}</TableRowColumn>
                          <TableRowColumn>{candidate.paymentStatus}</TableRowColumn>
                        </TableRow>
                      );
                    })}
                  </TableBody>
                </Table>
              </div>
            </div>
          </form>
        </div>
      </MUIDialog>
    );
  }
}

export default withFormik({
  mapPropsToValues: props => {
    const { amountReceived, candidateSelection, candidatePayment } = props.candidateIDs.reduce(
      (acc, id) => {
        const candidate = props.candidates[id];

        return {
          amountReceived: candidate.amountDue + acc.amountReceived,
          candidateSelection: {
            ...acc.candidateSelection,
            [id]: true
          },
          candidatePayment: {
            ...acc.candidatePayment,
            [`payment-${id}`]: candidate.amountDue
          }
        };
      },
      {
        amountReceived: 0,
        candidateSelection: {},
        candidatePayment: {}
      }
    );

    return {
      company: '',
      companyId: '',
      type: '',
      checkNumber: '',
      amountReceived,
      applyPercentageAdjustment: false,
      percentageAdjustment: '',
      candidateSelection,
      ...candidatePayment
    };
  },
  validationSchema: props => {
    const topLevelSchema = Yup.lazy(values => {
      const { candidateSelectionSchema, candidatePaymentSchema, totalAmountDuesPaid } = props.candidateIDs.reduce(
        (acc, id) => ({
          candidateSelectionSchema: getIterResultCandidateSelectionSchema(acc.candidateSelectionSchema, id),
          candidatePaymentSchema: getIterResultCandidatePaymentSchema(
            acc.candidatePaymentSchema,
            id,
            values.candidateSelection,
            props.candidates
          ),
          totalAmountDuesPaid: getIterResultTotalAmountDuesPaid(acc.totalAmountDuesPaid, id, values)
        }),
        {
          candidateSelectionSchema: {},
          candidatePaymentSchema: {},
          totalAmountDuesPaid: 0
        }
      );

      const excessAllowance = values.amountReceived * 0.02;
      const minAmountReceived = totalAmountDuesPaid - excessAllowance;

      const amountReceivedSchema = Yup.number()
        .moreThan(0, 'Amount Received must be greater than $0.')
        .min(
          minAmountReceived,
          'Amount (+2%) must be greater than or equal to total amount dues per Candidate to be paid for.'
        )
        .required('Amount Received is required.');

      return Yup.object().shape({
        company: Yup.string().required('Company Name is required.'),
        companyId: Yup.number().required('Company ID is required.'),
        type: Yup.string()
          .oneOf(companyPaymentTxTypes, 'Please select a valid Payment Type.')
          .required('Please select a valid Payment Type.'),
        checkNumber: Yup.string(),
        amountReceived: amountReceivedSchema,
        applyPercentageAdjustment: Yup.boolean().required(),
        percentageAdjustment: Yup.number().when('applyPercentageAdjustment', (applyPercentageAdjustment, schema) => {
          return applyPercentageAdjustment ? schema.required('Percentage Adjustment is required.') : schema;
        }),
        candidateSelection: Yup.object().shape(candidateSelectionSchema),
        ...candidatePaymentSchema
      });
    });

    return topLevelSchema;
  },
  validateOnChange: false,
  validateOnBlur: true,
  handleSubmit: async (values, { props }) => {
    const candidate_transactions = Object.keys(values.candidateSelection).reduce((acc, id) => {
      if (values.candidateSelection[id]) {
        return [
          ...acc,
          {
            candidate_id: id,
            amount: values[`payment-${id}`]
          }
        ];
      }
      return acc;
    }, []);

    const payload = {
      company_id: values.companyId,
      type: values.type,
      amount: values.amountReceived,
      candidate_transactions
    };

    if (values.type === 'PAYMENT_CHECK' && values.checkNumber) {
      payload.check_number = values.checkNumber;
    }

    try {
      await axios.post(`/admin/company/add-transaction`, payload);
      window.location.reload();
    } catch (e) {
      console.error(e);
    }
  }
})(CompanyPaymentDialog);
