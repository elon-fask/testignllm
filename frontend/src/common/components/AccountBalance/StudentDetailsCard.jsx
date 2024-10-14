import React from 'react';
import { formatMoney } from 'accounting';
import { Card, CardHeader, CardText } from 'material-ui/Card';
import ActionsRow from './ActionsRow';

const StudentDetailsCard = props => (
  <Card style={{ marginBottom: '20px' }}>
    <CardHeader
      title="Student Details"
      style={{ backgroundColor: 'rgb(232, 232, 232)' }}
      titleStyle={{ fontSize: '18px' }}
    />
    <CardText>
      <div style={{ display: 'flex' }}>
        <div style={{ marginRight: '20px', textAlign: 'right' }}>
          <div>Phone</div>
          <div>Application Type</div>
          <div>Price</div>
          <div>Remaining Amount</div>
          <div>PO Number</div>
        </div>
        <div style={{ fontWeight: 'bold' }}>
          <div>{props.cellNumber}</div>
          <div>{props.applicationType}</div>
          <div>{formatMoney(props.customerCharges)}</div>
          <div>{formatMoney(props.amountDue)}</div>
          <div>{props.purchaseOrderNumber}</div>
        </div>
      </div>
      <ActionsRow
        createTransaction={props.createTransaction}
        idHash={props.idHash}
        maxDiscount={props.amountDue}
        maxRefund={props.customerCharges}
      />
    </CardText>
  </Card>
);

export default StudentDetailsCard;
