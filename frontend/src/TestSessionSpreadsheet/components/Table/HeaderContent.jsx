import React from 'react';

const cellMappings = {
  name: (
    <td key="name">
      <div className="content-cell content-cell--name">Name</div>
    </td>
  ),
  company: (
    <td key="company">
      <div className="content-cell content-cell--company">Company</div>
    </td>
  ),
  applicationType: (
    <td key="applicationType">
      <div className="content-cell content-cell--application-type">Type</div>
    </td>
  ),
  coreEnabled: (
    <td key="coreEnabled">
      <div className="content-cell content-cell-boolean">Core</div>
    </td>
  ),
  writtenSWEnabled: (
    <td key="writtenSWEnabled">
      <div className="content-cell content-cell-boolean">SW</div>
    </td>
  ),
  writtenFXEnabled: (
    <td key="writtenFXEnabled">
      <div className="content-cell content-cell-boolean">FX</div>
    </td>
  ),
  numCranesSW: (
    <td key="numCranesSW">
      <div className="content-cell content-cell-boolean">SW Cab</div>
    </td>
  ),
  numCranesFX: (
    <td key="numCranesFX">
      <div className="content-cell content-cell-boolean">FX Cab</div>
    </td>
  ),
  practicalCharges: (
    <td key="practicalCharges">
      <div className="content-cell">Practical Charges</div>
    </td>
  ),
  practicalRetestFee: (
    <td key="practicalRetestFee">
      <div className="content-cell">Practical Retest</div>
    </td>
  ),
  writtenCharges: (
    <td key="writtenCharges">
      <div className="content-cell">Testing</div>
    </td>
  ),
  lateFee: (
    <td key="lateFee">
      <div className="content-cell">Late Fee</div>
    </td>
  ),
  incompleteFee: (
    <td key="incompleteFee">
      <div className="content-cell">Incomplete Fee</div>
    </td>
  ),
  walkInFee: (
    <td key="walkInFee">
      <div className="content-cell">Walk-in Fee</div>
    </td>
  ),
  otherFee: (
    <td key="otherFee">
      <div className="content-cell">Other Fee</div>
    </td>
  ),
  practiceTimeCharge: (
    <td key="practiceTimeCharge">
      <div className="content-cell">Practice Time Charges</div>
    </td>
  ),
  customerCharges: (
    <td key="customerCharges">
      <div className="content-cell">Customer Charges</div>
    </td>
  ),
  amountPaid: (
    <td key="amountPaid">
      <div className="content-cell">Paid</div>
    </td>
  ),
  amountDue: (
    <td key="amountDue">
      <div className="content-cell content-cell-currency">Amount Due</div>
    </td>
  ),
  paymentStatus: (
    <td key="paymentStatus">
      <div className="content-cell content-cell-payment-status">Payment Status</div>
    </td>
  ),
  payeeType: (
    <td key="payeeType">
      <div className="content-cell content-cell-payee-type">Payee Type</div>
    </td>
  ),
  invoiceNumber: (
    <td key="invoiceNumber">
      <div className="content-cell content-cell-invoice-number">Invoice #</div>
    </td>
  ),
  purchaseOrderNumber: (
    <td key="purchaseOrderNumber">
      <div className="content-cell content-cell-po-number">Purchase Order #</div>
    </td>
  ),
  gradeCore: (
    <td key="gradeCore">
      <div className="content-cell">Core</div>
    </td>
  ),
  gradeWrittenSW: (
    <td key="gradeWrittenSW">
      <div className="content-cell">SW</div>
    </td>
  ),
  gradeWrittenFX: (
    <td key="gradeWrittenFX">
      <div className="content-cell">FX</div>
    </td>
  ),
  gradePracticalSW: (
    <td key="gradePracticalSW">
      <div className="content-cell">SW Cab</div>
    </td>
  ),
  gradePracticalFX: (
    <td key="gradePracticalFX">
      <div className="content-cell">FX Cab</div>
    </td>
  ),
  cellPhone: (
    <td key="cellPhone">
      <div className="content-cell content-cell-cellphone">Cell Phone</div>
    </td>
  ),
  notes: (
    <td key="notes">
      <div className="content-cell content-cell-notes">Notes</div>
    </td>
  ),
  practice: (
    <td key="practice">
      <div className="content-cell content-cell-practice">Practice</div>
    </td>
  ),
  signedWFormReceived: (
    <td key="signedWFormReceived">
      <div className="content-cell">Signed Written Form Received</div>
    </td>
  ),
  signedPFormReceived: (
    <td key="signedPFormReceived">
      <div className="content-cell">Signed Practical Form Received</div>
    </td>
  ),
  confirmationEmailLastSent: (
    <td key="confirmationEmailLastSent">
      <div className="content-cell">Confirmation Email Sent</div>
    </td>
  ),
  appFormSentToNccco: (
    <td key="appFormSentToNccco">
      <div className="content-cell">Application Form Sent to NCCCO</div>
    </td>
  ),
  practicalScheduleDay: (
    <td key="practicalScheduleDay">
      <div className="content-cell">Day</div>
    </td>
  ),
  practicalScheduleDate: (
    <td key="practicalScheduleDate">
      <div className="content-cell">Date</div>
    </td>
  ),
  practicalScheduleTime: (
    <td key="practicalScheduleTime">
      <div className="content-cell">Time</div>
    </td>
  ),
  practicalScheduleNewOrRetest: (
    <td key="practicalScheduleNewOrRetest">
      <div className="content-cell">New or Retest</div>
    </td>
  ),
  actions: (
    <td key="actions" className="no-print">
      <div className="content-cell">Actions</div>
    </td>
  )
};

const HeaderContent = ({ content }) => cellMappings[content];

export default HeaderContent;
