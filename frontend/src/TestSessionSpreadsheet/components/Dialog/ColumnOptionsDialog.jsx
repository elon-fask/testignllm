import React from 'react';
import { withFormik, Field } from 'formik';
import MUIDialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import { views, viewTypes, viewOptionsKeys } from '../../reducers/ui';
import Checkbox from '../../../common/components/formik/Checkbox';

const columnNames = {
  company: 'Company',
  applicationType: 'Application Type',
  coreEnabled: 'Core Exam Enabled',
  writtenSWEnabled: 'Written SW Enabled',
  writtenFXEnabled: 'Written FX Enabled',
  numCranesSW: 'Practical SW Attempts',
  numCranesFX: 'Practical FX Attempts',
  practicalCharges: 'Practical Charges',
  practicalRetestFee: 'Practical Retest Fee',
  writtenCharges: 'Written Testing Fees',
  lateFee: 'Late Fee',
  incompleteFee: 'Incomplete/Change Fee',
  walkInFee: 'Walk-in Fee',
  otherFee: 'Other Fee',
  practiceTimeCharge: 'Practice Time Charges',
  customerCharges: 'Customer Charges',
  amountPaid: 'Amount Paid',
  amountDue: 'Amount Due',
  paymentStatus: 'Payment Status',
  invoiceNumber: 'Invoice #',
  purchaseOrderNumber: 'Purchase Order #',
  gradeCore: 'Grade Core Exam',
  gradeWrittenSW: 'Grade Written SW',
  gradeWrittenFX: 'Grade Written FX',
  gradePracticalSW: 'Grade Practical SW',
  gradePracticalFX: 'Grade Practical FX',
  signedWFormReceived: 'Signed Written Form Received',
  signedPFormReceived: 'Signed Practical Form Received',
  confirmationEmailLastSent: 'Confirmation Email Sent',
  appFormSentToNccco: 'Application Form Sent to NCCCO',
  cellPhone: 'Cell Phone',
  notes: 'Notes',
  practice: 'Practice'
};

const viewOptionLabels = {
  showTestSessionInfo: 'Show Test Session Info',
  combineStudents: 'Combine Regular and Practical-only Students',
  countPracticalCranes: 'Show Practical Exam Attempt Counts',
  showTotalsTable: 'Show Totals Table',
  showSummaryTable: 'Show Summary Table'
};

const columns = views[viewTypes.ALL];

const ColumnOptionsDialog = props => {
  const actions = [
    <FlatButton label="Cancel" primary onTouchTap={props.closeDialog} style={{ marginRight: '20px' }} />,
    <RaisedButton label="OK" primary onTouchTap={props.handleSubmit} />
  ];

  return (
    <MUIDialog title="Column Options" actions={actions} modal open={props.isOpen} autoScrollBodyContent>
      <div>
        <h4>Components</h4>
        <h4>Visible Columns</h4>
        <div style={{ display: 'flex', flexWrap: 'wrap' }}>
          {columns.map(col => (
            <Field key={col} name={col} label={columnNames[col]} component={Checkbox} style={{ flexBasis: '50%' }} />
          ))}
        </div>
      </div>
    </MUIDialog>
  );
};

export default withFormik({
  handleSubmit: (values, { props }) => {
    const payload = columns.filter(colName => values[colName]);

    props.setVisibleColumns(payload);
  },
  mapPropsToValues: props => ({
    ...Object.keys(columnNames).reduce(
      (acc, colName) => ({
        ...acc,
        [colName]: props.visibleColumns.includes(colName)
      }),
      {}
    )
  })
})(ColumnOptionsDialog);
