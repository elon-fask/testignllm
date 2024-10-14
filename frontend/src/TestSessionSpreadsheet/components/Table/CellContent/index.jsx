import React from 'react';
import { formatMoney } from 'accounting';
import BaseCell from '../../BaseCell';
import { cellTypes, COL_NUM_MAPPING } from '../../../lib/refs';
import {
  getNewOrRetest,
  getGradeCellStyle,
  getPaymentStatusStyles,
  getPracticeTimeValue,
  getPayeeType
} from './helpers';
import ActionsCell from '../../EnhancedCell/ActionsCell';

const CellContent = props => {
  const { commonProps, numCranesSWVal, numCranesFXVal, grades, setRowHeight } = props;

  const cellMappings = {
    name: (
      <BaseCell
        key="name"
        contentClassName="content-cell content-cell-name"
        col={COL_NUM_MAPPING.name}
        type={cellTypes.EDITABLE}
        value={props.candidate.name}
        {...commonProps}
      />
    ),
    company: (
      <BaseCell
        key="company"
        contentClassName="content-cell content-cell-company"
        col={COL_NUM_MAPPING.company}
        type={cellTypes.EDITABLE}
        value={props.candidate.company}
        {...commonProps}
      />
    ),
    applicationType: (
      <BaseCell
        key="applicationType"
        col={COL_NUM_MAPPING.applicationType}
        type={cellTypes.DIALOG}
        value={props.applicationType.name}
        {...commonProps}
      />
    ),
    coreEnabled: (
      <BaseCell
        key="coreEnabled"
        contentClassName="content-cell content-cell-boolean"
        style={{ textAlign: 'center' }}
        col={COL_NUM_MAPPING.coreEnabled}
        type={cellTypes.DIALOG}
        value={props.candidate.mergedFormSetup.coreEnabled ? 'x' : ''}
        {...commonProps}
      />
    ),
    writtenSWEnabled: (
      <BaseCell
        key="writtenSWEnabled"
        contentClassName="content-cell content-cell-boolean"
        style={{ textAlign: 'center' }}
        col={COL_NUM_MAPPING.writtenSWEnabled}
        type={cellTypes.DIALOG}
        value={props.candidate.mergedFormSetup.writtenSWEnabled ? 'x' : ''}
        {...commonProps}
      />
    ),
    writtenFXEnabled: (
      <BaseCell
        key="writtenFXEnabled"
        contentClassName="content-cell content-cell-boolean"
        style={{ textAlign: 'center' }}
        col={COL_NUM_MAPPING.writtenFXEnabled}
        type={cellTypes.DIALOG}
        value={props.candidate.mergedFormSetup.writtenFXEnabled ? 'x' : ''}
        {...commonProps}
      />
    ),
    numCranesSW: (
      <BaseCell
        key="numCranesSW"
        contentClassName="content-cell content-cell-boolean"
        style={{ textAlign: 'center' }}
        col={COL_NUM_MAPPING.numCranesSW}
        type={cellTypes.DIALOG}
        value={numCranesSWVal}
        {...commonProps}
      />
    ),
    numCranesFX: (
      <BaseCell
        key="numCranesFX"
        contentClassName="content-cell content-cell-boolean"
        style={{ textAlign: 'center' }}
        col={COL_NUM_MAPPING.numCranesFX}
        type={cellTypes.DIALOG}
        value={numCranesFXVal}
        {...commonProps}
      />
    ),
    practicalCharges: (
      <BaseCell
        key="practicalCharges"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.practicalCharges}
        type={cellTypes.DIALOG}
        value={props.candidate.practicalCharges ? formatMoney(props.candidate.practicalCharges) : '--'}
        {...commonProps}
      />
    ),
    practicalRetestFee: (
      <BaseCell
        key="practicalRetestFee"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.practicalRetestFee}
        type={cellTypes.DIALOG}
        value={props.candidate.practicalRetestFee ? formatMoney(props.candidate.practicalRetestFee) : '--'}
        {...commonProps}
      />
    ),
    writtenCharges: (
      <BaseCell
        key="writtenCharges"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.writtenCharges}
        type={cellTypes.DIALOG}
        value={props.candidate.writtenCharges ? formatMoney(props.candidate.writtenCharges) : '--'}
        {...commonProps}
      />
    ),
    lateFee: (
      <BaseCell
        key="lateFee"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.lateFee}
        type={cellTypes.DIALOG}
        value={props.candidate.lateFee ? formatMoney(props.candidate.lateFee) : '--'}
        {...commonProps}
      />
    ),
    incompleteFee: (
      <BaseCell
        key="incompleteFee"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.incompleteFee}
        type={cellTypes.DIALOG}
        value={props.candidate.incompleteFee ? formatMoney(props.candidate.incompleteFee) : '--'}
        {...commonProps}
      />
    ),
    walkInFee: (
      <BaseCell
        key="walkInFee"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.walkInFee}
        type={cellTypes.DIALOG}
        value={props.candidate.walkInFee ? formatMoney(props.candidate.walkInFee) : '--'}
        {...commonProps}
      />
    ),
    otherFee: (
      <BaseCell
        key="otherFee"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.otherFee}
        type={cellTypes.DIALOG}
        value={props.candidate.otherFee ? formatMoney(props.candidate.otherFee) : '--'}
        {...commonProps}
      />
    ),
    practiceTimeCharge: (
      <BaseCell
        key="practiceTimeCharge"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.practiceTimeCharge}
        type={cellTypes.DIALOG}
        value={props.candidate.practiceTimeCharge ? formatMoney(props.candidate.practiceTimeCharge) : '--'}
        {...commonProps}
      />
    ),
    customerCharges: (
      <BaseCell
        key="customerCharges"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.customerCharges}
        type={cellTypes.DIALOG}
        value={formatMoney(props.candidate.customerCharges)}
        {...commonProps}
      />
    ),
    amountPaid: (
      <BaseCell
        key="amountPaid"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.amountPaid}
        type={cellTypes.DIALOG}
        value={formatMoney(props.candidate.amountPaid)}
        {...commonProps}
      />
    ),
    amountDue: (
      <BaseCell
        key="amountDue"
        contentClassName="content-cell content-cell-currency"
        col={COL_NUM_MAPPING.amountDue}
        type={cellTypes.DIALOG}
        value={formatMoney(props.candidate.amountDue)}
        {...commonProps}
      />
    ),
    paymentStatus: (
      <BaseCell
        key="paymentStatus"
        col={COL_NUM_MAPPING.paymentStatus}
        type={cellTypes.DIALOG}
        style={getPaymentStatusStyles(props.candidate.paymentStatus)}
        value={props.candidate.paymentStatus}
        {...commonProps}
      />
    ),
    payeeType: (
      <BaseCell
        key="payeeType"
        col={COL_NUM_MAPPING.payeeType}
        type={cellTypes.DIALOG}
        value={getPayeeType(props.candidate.isCompanySponsored)}
        {...commonProps}
      />
    ),
    invoiceNumber: (
      <BaseCell
        key="invoiceNumber"
        contentClassName="content-cell content-cell-invoice-number"
        col={COL_NUM_MAPPING.invoiceNumber}
        type={cellTypes.EDITABLE}
        value={props.candidate.invoiceNumber}
        {...commonProps}
      />
    ),
    purchaseOrderNumber: (
      <BaseCell
        key="purchaseOrderNumber"
        contentClassName="content-cell content-cell-po-number"
        col={COL_NUM_MAPPING.purchaseOrderNumber}
        type={cellTypes.EDITABLE}
        value={props.candidate.purchaseOrderNumber}
        {...commonProps}
      />
    ),
    gradeCore: (
      <BaseCell
        key="gradeCore"
        col={COL_NUM_MAPPING.gradeCore}
        type={cellTypes.DIALOG}
        style={getGradeCellStyle(grades.W_EXAM_CORE)}
        value={grades.W_EXAM_CORE}
        {...commonProps}
      />
    ),
    gradeWrittenSW: (
      <BaseCell
        key="gradeWrittenSW"
        col={COL_NUM_MAPPING.gradeWrittenSW}
        type={cellTypes.DIALOG}
        style={getGradeCellStyle(grades.W_EXAM_TLL)}
        value={grades.W_EXAM_TLL}
        {...commonProps}
      />
    ),
    gradeWrittenFX: (
      <BaseCell
        key="gradeWrittenFX"
        col={COL_NUM_MAPPING.gradeWrittenFX}
        type={cellTypes.DIALOG}
        style={getGradeCellStyle(grades.W_EXAM_TSS)}
        value={grades.W_EXAM_TSS}
        {...commonProps}
      />
    ),
    gradePracticalSW: (
      <BaseCell
        key="gradePracticalSW"
        col={COL_NUM_MAPPING.gradePracticalSW}
        type={cellTypes.DIALOG}
        style={getGradeCellStyle(grades.P_TELESCOPIC_TLL)}
        value={grades.P_TELESCOPIC_TLL}
        {...commonProps}
      />
    ),
    gradePracticalFX: (
      <BaseCell
        key="gradePracticalFX"
        col={COL_NUM_MAPPING.gradePracticalFX}
        type={cellTypes.DIALOG}
        style={getGradeCellStyle(grades.P_TELESCOPIC_TSS)}
        value={grades.P_TELESCOPIC_TSS}
        {...commonProps}
      />
    ),
    cellPhone: (
      <BaseCell
        key="cellPhone"
        contentClassName="content-cell content-cell-cellphone"
        col={COL_NUM_MAPPING.cellPhone}
        type={cellTypes.READONLY}
        value={props.candidate.cellNumber}
        {...commonProps}
      />
    ),
    notes: (
      <BaseCell
        key="notes"
        contentClassName="content-cell content-cell-notes"
        col={COL_NUM_MAPPING.notes}
        type={cellTypes.EDITABLE}
        value={props.candidate.instructorNotes}
        {...commonProps}
      />
    ),
    practice: (
      <BaseCell
        key="practice"
        contentClassName="content-cell content-cell-practice"
        col={COL_NUM_MAPPING.practice}
        type={cellTypes.DIALOG}
        value={getPracticeTimeValue(props.candidate)}
        {...commonProps}
      />
    ),
    signedWFormReceived: (
      <BaseCell
        key="signedWFormReceived"
        col={COL_NUM_MAPPING.signedWFormReceived}
        type={cellTypes.DIALOG}
        value={props.candidate.signedWFormReceived}
        {...commonProps}
      />
    ),
    signedPFormReceived: (
      <BaseCell
        key="signedPFormReceived"
        col={COL_NUM_MAPPING.signedPFormReceived}
        type={cellTypes.DIALOG}
        value={props.candidate.signedPFormReceived}
        {...commonProps}
      />
    ),
    confirmationEmailLastSent: (
      <BaseCell
        key="confirmationEmailLastSent"
        col={COL_NUM_MAPPING.confirmationEmailLastSent}
        type={cellTypes.DIALOG}
        value={props.candidate.confirmationEmailLastSent}
        {...commonProps}
      />
    ),
    appFormSentToNccco: (
      <BaseCell
        key="appFormSentToNccco"
        col={COL_NUM_MAPPING.appFormSentToNccco}
        type={cellTypes.DIALOG}
        value={props.candidate.appFormSentToNccco}
        {...commonProps}
      />
    ),
    practicalScheduleDay: (
      <BaseCell
        key="practicalScheduleDay"
        col={COL_NUM_MAPPING.practicalScheduleDay}
        type={cellTypes.READONLY}
        value={props.candidate.testSchedule ? props.candidate.testSchedule.day : null}
        {...commonProps}
      />
    ),
    practicalScheduleDate: (
      <BaseCell
        key="practicalScheduleDate"
        col={COL_NUM_MAPPING.practicalScheduleDate}
        type={cellTypes.READONLY}
        value={props.candidate.testSchedule ? props.candidate.testSchedule.date : null}
        {...commonProps}
      />
    ),
    practicalScheduleTime: (
      <BaseCell
        key="practicalScheduleTime"
        col={COL_NUM_MAPPING.practicalScheduleTime}
        type={cellTypes.READONLY}
        value={props.candidate.testSchedule ? props.candidate.testSchedule.time : null}
        {...commonProps}
      />
    ),
    practicalScheduleNewOrRetest: (
      <BaseCell
        key="practicalScheduleNewOrRetest"
        col={COL_NUM_MAPPING.practicalScheduleNewOrRetest}
        type={cellTypes.READONLY}
        value={props.candidate.testSchedule ? getNewOrRetest(props.candidate.testSchedule) : ''}
        {...commonProps}
      />
    ),
    actions: <ActionsCell commonProps={commonProps} setRowHeight={setRowHeight} candidate={props.candidate} />
  };

  return cellMappings[props.content];
};

export default CellContent;
