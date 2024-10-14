import React, { Fragment } from 'react';
import { connect, Field } from 'formik';
import { companyPaymentTxTypes, companyTxTypesStr } from '../../../../common/companyTransactions';
import TextField from '../../../../common/components/formik/bootstrap/TextField';
import OrderedSelectField from '../../../../common/components/formik/bootstrap/OrderedSelectField';
import CheckboxField from '../../../../common/components/formik/bootstrap/CheckboxField';

const paymentTypeOptions = companyPaymentTxTypes.map(txType => ({
  key: txType,
  value: txType,
  text: companyTxTypesStr[txType]
}));

function FieldsSection(props) {
  const { values } = props.formik;

  const companyOptions = props.companiesById.map(id => {
    const { id: companyId, name } = props.companies[id];

    return {
      key: companyId,
      value: companyId,
      text: name
    };
  });

  return (
    <Fragment>
      <Field
        name="companyId"
        label="Company Name"
        options={companyOptions}
        component={OrderedSelectField}
        labelStyle={{ fontWeight: 'normal', width: '100%' }}
      />
      <Field
        name="type"
        label="Payment Type"
        options={paymentTypeOptions}
        component={OrderedSelectField}
        labelStyle={{ fontWeight: 'normal', width: '100%' }}
      />
      {values.type === 'PAYMENT_CHECK' && <Field name="checkNumber" label="Check Number" component={TextField} />}
      <Field name="amountReceived" type="number" label="Amount Received" component={TextField} />
      <Field name="applyPercentageAdjustment" label="Apply a Percentage Adjustment" component={CheckboxField} />
      {values.applyPercentageAdjustment && (
        <Field name="percentageAdjustment" type="number" label="Percentage Adjustment (%)" component={TextField} />
      )}
    </Fragment>
  );
}

export default connect(FieldsSection);
