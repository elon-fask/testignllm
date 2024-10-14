import React from 'react';
import moment from 'moment';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import { testSiteTypeStrMapping } from '../../../common/testSite';
import OrderedSelectField from '../../../common/components/formik/bootstrap/OrderedSelectField';
import DateTimePickerField from '../../../common/components/formik/bootstrap/DateTimePickerField';

function SearchForm(props) {
  const { handleSubmit } = props;

  const testSiteOptions = props.testSites.map(testSite => {
    const testSiteTypeSr = testSiteTypeStrMapping[testSite.type];

    return {
      key: testSite.id,
      value: testSite.id,
      text: `${testSite.name} - ${testSite.city}, ${testSite.state} (${testSiteTypeSr})`
    };
  });

  return (
    <form onSubmit={handleSubmit} style={{ display: 'flex', alignItems: 'center' }}>
      <Field
        name="testSiteId"
        label="Test Site"
        options={testSiteOptions}
        component={OrderedSelectField}
        style={{ marginRight: '16px' }}
        disableBlank={false}
      />
      <Field
        name="startDate"
        label="Start Date"
        component={DateTimePickerField}
        options={{ format: 'YYYY-MM-DD' }}
        style={{ marginRight: '16px' }}
      />
      <Field
        name="endDate"
        label="End Date"
        component={DateTimePickerField}
        options={{ format: 'YYYY-MM-DD' }}
        style={{ marginRight: '16px' }}
      />
      <div>
        <button type="submit" className="btn btn-primary">
          Search
        </button>
      </div>
    </form>
  );
}

export default withFormik({
  mapPropsToValues: () => {
    const dateNow = moment();
    const endDate = dateNow.format('YYYY-MM-DD');
    const startDate = dateNow.subtract(3, 'months').format('YYYY-MM-DD');

    return {
      testSiteId: '',
      startDate,
      endDate
    };
  },
  validationSchema: Yup.object().shape({
    startDate: Yup.string().required('Start Date is required.'),
    endDate: Yup.string().required('End Date is required.')
  }),
  handleSubmit: (values, { props }) => {
    props.searchTestSessions(values);
    props.hideSection();
  }
})(SearchForm);
