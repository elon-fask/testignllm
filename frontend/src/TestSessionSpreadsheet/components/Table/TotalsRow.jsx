import React from 'react';
import { formatMoney } from 'accounting';
import { viewTypes } from '../../reducers/ui';

const TotalsRow = props => {
  const mapping = {
    name: <td key="name" style={{ border: 'none', backgroundColor: '#fff' }} />,
    company: <td key="company" style={{ border: 'none', backgroundColor: '#fff' }} />,
    applicationType: <td key="applicationType" style={{ border: 'none', backgroundColor: '#fff' }} />,
    coreEnabled: (
      <td key="coreEnabled" style={{ textAlign: 'center' }}>
        {props.numCoreExam}
      </td>
    ),
    writtenSWEnabled: (
      <td key="writtenSWEnabled" style={{ textAlign: 'center' }}>
        {props.numCranesWrittenSW}
      </td>
    ),
    writtenFXEnabled: (
      <td key="writtenFXEnabled" style={{ textAlign: 'center' }}>
        {props.numCranesWrittenFX}
      </td>
    ),
    numCranesSW: (
      <td key="numCranesSW" style={{ textAlign: 'center' }}>
        {props.numCranesPracticalSW}
      </td>
    ),
    numCranesFX: (
      <td key="numCranesFX" style={{ textAlign: 'center' }}>
        {props.numCranesPracticalFX}
      </td>
    ),
    practicalCharges: <td key="practicalCharges">{formatMoney(props.totalPracticalCharges)}</td>,
    practicalRetestFee: <td key="practicalRetestFee">{formatMoney(props.totalPracticalRetestFee)}</td>,
    writtenCharges: <td key="writtenCharges">{formatMoney(props.totalWrittenNcccoFees)}</td>,
    lateFee: <td key="lateFee">{formatMoney(props.totalLateFee)}</td>,
    incompleteFee: <td key="incompleteFee">{formatMoney(props.totalIncompleteFee)}</td>,
    walkInFee: <td key="walkInFee">{formatMoney(props.totalWalkInFee)}</td>,
    otherFee: <td key="otherFee">{formatMoney(props.totalOtherFee)}</td>,
    practiceTimeCharge: <td key="practiceTimeCharge">{formatMoney(props.totalPracticeTimeCharges)}</td>,
    customerCharges: <td key="customerCharges">{formatMoney(props.totalCustomerCharges)}</td>,
    amountPaid: <td key="amountPaid">{formatMoney(props.totalPaid)}</td>,
    amountDue: <td key="amountDue">{formatMoney(props.totalDue)}</td>,
    paymentStatus: <td key="paymentStatus" style={{ border: 'none', backgroundColor: '#fff' }} />,
    invoiceNumber: <td key="invoiceNumber" style={{ border: 'none', backgroundColor: '#fff' }} />,
    purchaseOrderNumber: <td key="purchaseOrderNumber" style={{ border: 'none', backgroundColor: '#fff' }} />,
    gradeCore: <td key="gradeCore" style={{ border: 'none', backgroundColor: '#fff' }} />,
    gradeWrittenSW: <td key="gradeWrittenSW" style={{ border: 'none', backgroundColor: '#fff' }} />,
    gradeWrittenFX: <td key="gradeWrittenFX" style={{ border: 'none', backgroundColor: '#fff' }} />,
    gradePracticalSW: <td key="gradePracticalSW" style={{ border: 'none', backgroundColor: '#fff' }} />,
    gradePracticalFX: <td key="gradePracticalFX" style={{ border: 'none', backgroundColor: '#fff' }} />,
    cellPhone: <td key="cellPhone" style={{ border: 'none', backgroundColor: '#fff' }} />,
    notes: <td key="notes" style={{ border: 'none', backgroundColor: '#fff' }} />,
    practice: <td key="practice" style={{ border: 'none', backgroundColor: '#fff' }} />,
    actions: <td key="actions" style={{ border: 'none', backgroundColor: '#fff' }} />
  };

  const defaultViewMapping = props.visibleColumns.map(col => {
    if (props.nameOnly && col === 'name') {
      return (
        <td key="name" colSpan={2}>
          Total Candidates: {props.candidateIDs.length}
        </td>
      );
    }

    if (props.nameOnly && col === 'company') {
      return undefined;
    }

    return mapping[col];
  });

  const viewMapping = {
    [viewTypes.DEFAULT]: defaultViewMapping,
    [viewTypes.GRADING]: defaultViewMapping,
    [viewTypes.CLASSREADINESS]: [
      <td key={0} style={{ border: 'none', backgroundColor: '#fff' }} />,
      <td key="coreEnabled" style={{ textAlign: 'center' }}>
        {props.numCoreExam}
      </td>,
      <td key="writtenSWEnabled" style={{ textAlign: 'center' }}>
        {props.numCranesWrittenSW}
      </td>,
      <td key="writtenFXEnabled" style={{ textAlign: 'center' }}>
        {props.numCranesWrittenFX}
      </td>,
      <td key="numCranesSW" style={{ textAlign: 'center' }}>
        {props.numCranesPracticalSW}
      </td>,
      <td key="numCranesFX" style={{ textAlign: 'center' }}>
        {props.numCranesPracticalFX}
      </td>
    ],
    NOGRADES: defaultViewMapping,
    BOOKKEEPING: defaultViewMapping,
    APPFORMS: defaultViewMapping,
    ALL: defaultViewMapping,
    CUSTOM: defaultViewMapping
  };

  return <tr style={{ backgroundColor: '#D8E3BC' }}>{viewMapping[props.view]}</tr>;
};

export default TotalsRow;
