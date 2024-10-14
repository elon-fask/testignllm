import React from 'react';
import MUIDialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import OrderedSelectField from '../../../common/components/formik/OrderedSelectField';
import { COL_NUM_MAPPING } from '../../lib/refs';

const options = [
  {
    key: 'blank',
    value: '',
    text: '(Not set)'
  },
  {
    key: '1',
    value: '1',
    text: 'Company'
  },
  {
    key: '0',
    value: '0',
    text: 'Individual'
  }
];

function PayeeTypeDialog(props) {
  return (
    <MUIDialog
      title={`Payee Type - ${props.candidate.name}`}
      modal
      open={props.isOpen}
      contentStyle={{ maxWidth: '500px' }}
      actions={[
        <RaisedButton primary label="Close" style={{ marginRight: '20px' }} onClick={props.closeDialog} />,
        <RaisedButton primary label="Submit" onClick={props.handleSubmit} />
      ]}
    >
      <Field name="selectedType" label="Payee Type" options={options} component={OrderedSelectField} />
    </MUIDialog>
  );
}

export default withFormik({
  mapPropsToValues: ({ candidate }) => ({
    selectedType: candidate.isCompanySponsored || ''
  }),
  handleSubmit: (values, { props }) => {
    props.blurCell(props.candidate.id, values.selectedType, COL_NUM_MAPPING.payeeType);
  },
  validationSchema: Yup.object().shape({
    selectedType: Yup.string('Please select either Company or Individual.')
      .oneOf(['1', '0'], 'Please select either Company or Individual.')
      .required('Please select either Company or Individual.')
  })
})(PayeeTypeDialog);
