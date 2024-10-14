import axios from 'axios';
import * as React from 'react';
import styled from 'styled-components';
import { v1 as uuidv1 } from 'uuid';
import { withFormik, Field, FormikProps } from 'formik';
import * as Yup from 'yup';
import { format, parse as parseDateFns } from 'date-fns';
import { parse as parseQueryStr } from 'query-string';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSpinner } from '@fortawesome/free-solid-svg-icons/faSpinner';
import { useEffectDidUpdate } from '../../../common/hooks';
import { ACTION_TYPES } from '../../context';
import DateField from '../../../common/components/formik/bulma/DateField';
import RadioGroup from '../../../common/components/formik/bulma/RadioGroup';

const Card = styled.div`
  margin-bottom: 1rem;
`;

const Tag = styled.span`
  margin: 0.5rem;
`;

type RowCategory = '' | 'candidate' | 'user' | 'testSession' | 'testSite';

const rowOptions = [
  { value: 'candidate', label: 'Candidates' },
  // { value: 'user', label: 'Staff' },
  { value: 'testSession', label: 'Test Session' }
  // { value: 'testSite', label: 'Test Site' }
];

const rowValueOptions = {
  candidate: [{ value: 'fullName', label: 'Full Name' }],
  user: [{ value: 'fullName', label: 'Full Name' }],
  testSession: [{ value: 'name', label: 'Class Date & Location' }],
  testSite: [{ value: 'name', label: 'Test Site Name' }]
};

type ColumnCategory = '' | 'candidate' | 'user' | 'testSession' | 'testSite';

const columnOptions = {
  candidate: [
    { value: 'candidate', label: 'Candidates' },
    { value: 'user', label: 'Staff' },
    { value: 'testSession', label: 'Test Session' },
    { value: 'testSite', label: 'Test Site' }
  ],
  testSession: [
    { value: 'candidate', label: 'Candidates' },
    { value: 'user', label: 'Staff' },
    { value: 'testSession', label: 'Test Session' },
    { value: 'testSite', label: 'Test Site' }
  ]
};

const columnValueOptions = {
  candidate: {
    candidate: [
      { value: 'email', label: 'Email' },
      { value: 'phone', label: 'Phone' },
      { value: 'mobilePhone', label: 'Mobile Phone' },
      { value: 'customerCharges', label: 'Customer Charges' },
      { value: 'totalPayment', label: 'Amount Paid' },
      { value: 'totalAmountOwed', label: 'Amount Owed' },
      { value: 'gradeCoreExam', label: 'Grade Core Exam' },
      { value: 'gradeWrittenFx', label: 'Grade Written FX' },
      { value: 'gradeWrittenSw', label: 'Grade Written SW' },
      { value: 'gradePracticalFx', label: 'Grade Practical FX' },
      { value: 'gradePracticalSw', label: 'Grade Practical SW' }
    ],
    user: [{ value: 'fullName', label: 'Full Name' }],
    testSession: [{ value: 'name', label: 'Class Date & Location' }],
    testSite: [{ value: 'name', label: 'Test Site Name' }]
  },
  testSession: {
    candidate: [
      { value: 'count', label: 'Count (No. of Candidates)' },
      { value: 'countRegular', label: 'Count (Regular Candidates Only)' },
      { value: 'countPracticalOnly', label: 'Count (Practical Only Candidates)' },
      { value: 'countSw', label: 'Count (SW Cab Only)' },
      { value: 'countFx', label: 'Count (FX Cab Only)' },
      { value: 'countCoreExamTotal', label: 'Count (Core Exam Test Takers)' },
      { value: 'countWrittenFxTotal', label: 'Count (Written FX Exam Test Takers)' },
      { value: 'countWrittenSwTotal', label: 'Count (Written SW Exam Test Takers)' },
      { value: 'countPracticalFxTotal', label: 'Count (Practical FX Exam Test Takers)' },
      { value: 'countPracticalSwTotal', label: 'Count (Practical SW Exam Test Takers)' },
      { value: 'countCoreExamPass', label: 'Count (Passed Core Exam)' },
      { value: 'countWrittenFxPass', label: 'Count (Passed Written FX Exam)' },
      { value: 'countWrittenSwPass', label: 'Count (Passed Written SW Exam)' },
      { value: 'countPracticalFxPass', label: 'Count (Passed Practical FX Exam)' },
      { value: 'countPracticalSwPass', label: 'Count (Passed Practical SW Exam)' },
      { value: 'countCoreExamFail', label: 'Count (Failed Core Exam)' },
      { value: 'countWrittenFxFail', label: 'Count (Failed Written FX Exam)' },
      { value: 'countWrittenSwFail', label: 'Count (Failed Written SW Exam)' },
      { value: 'countPracticalFxFail', label: 'Count (Failed Practical FX Exam)' },
      { value: 'countPracticalSwFail', label: 'Count (Failed Practical SW Exam)' },
      { value: 'countPracticalFxDecline', label: 'Count (Declined Practical FX Exam)' },
      { value: 'countPracticalSwDecline', label: 'Count (Declined Practical SW Exam)' }
    ],
    user: [
      { value: 'testCoordinator', label: 'Test Coordinator' },
      { value: 'proctor', label: 'Proctor' },
      { value: 'instructor', label: 'Instructor' },
      { value: 'practicalExaminer', label: 'Practical Examiner' }
    ],
    testSession: [{ value: 'name', label: 'Class Date & Location' }],
    testSite: [{ value: 'name', label: 'Test Site Name' }]
  }
};

interface CategoryValueMap {
  category: string;
  value: string;
}

interface RowCategoryValueMap extends CategoryValueMap {
  category: RowCategory;
  value: string;
}

interface ColumnCategoryValueMap extends CategoryValueMap {
  category: ColumnCategory;
}

interface FieldValues {
  from: string | Date;
  to: string | Date;
  row: RowCategoryValueMap;
  currentColumnSelection: ColumnCategoryValueMap;
  columns: ColumnCategoryValueMap[];
}

function Settings(props: FormikProps<FieldValues>) {
  const { from, to, row, columns, currentColumnSelection } = props.values;

  useEffectDidUpdate(() => {
    props.setFieldValue('row.value', '');
    props.setFieldValue('currentColumnSelection', { category: '', value: '' });
    props.setFieldValue('columns', []);
  }, [row.category]);

  useEffectDidUpdate(() => {
    props.setFieldValue('currentColumnSelection', { category: '', value: '' });
    props.setFieldValue('columns', []);
  }, [row.value]);

  React.useEffect(() => {
    if (from && to && row.category && row.value && columns.length > 0) {
      props.handleSubmit();
    }
  }, []);

  const handleAddColumnClick = () => {
    props.setFieldValue('columns', [...columns, currentColumnSelection]);
  };

  const handleRemoveColumnClick = i => () => {
    const newColumns = [...columns.slice(0, i), ...columns.slice(i + 1)];
    props.setFieldValue('columns', newColumns);
  };

  return (
    <form className="container" onSubmit={props.handleSubmit} style={{ marginBottom: '1rem' }}>
      <Card className="card">
        <header className="card-header">
          <p className="card-header-title">Data Date/Time Range</p>
        </header>
        <div className="card-content">
          <div className="columns">
            <div className="column">
              <Field name="from" label="Start Date" component={DateField} />
            </div>
            <div className="column">
              <Field name="to" label="End Date" component={DateField} />
            </div>
          </div>
        </div>
      </Card>
      <Card className="card">
        <header className="card-header">
          <p className="card-header-title">Rows (X-axis)</p>
        </header>
        <div className="card-content">
          <div style={{ marginBottom: '1rem' }}>
            <p className="has-text-weight-bold">Category/Object</p>
            <Field name="row.category" options={rowOptions} component={RadioGroup} />
          </div>
          {row.category && (
            <div className="animated fadeIn">
              <p className="has-text-weight-bold">Target Value</p>
              <Field name="row.value" options={rowValueOptions[row.category]} component={RadioGroup} />
            </div>
          )}
        </div>
      </Card>
      {row.category && row.value && columnOptions[row.category] && (
        <Card className="card">
          <header className="card-header">
            <p className="card-header-title">Column Options (Y-axis)</p>
          </header>
          <div className="card-content">
            <div style={{ marginBottom: '1.5rem' }}>
              <div style={{ marginBottom: '1rem' }}>
                <p className="has-text-weight-bold">Category/Object</p>
                <Field
                  name="currentColumnSelection.category"
                  options={columnOptions[row.category]}
                  component={RadioGroup}
                />
              </div>
              {currentColumnSelection.category && (
                <div className="animated fadeIn">
                  <p className="has-text-weight-bold">Target Value</p>
                  <Field
                    name="currentColumnSelection.value"
                    options={columnValueOptions[row.category][currentColumnSelection.category]}
                    component={RadioGroup}
                  />
                </div>
              )}
              <button
                type="button"
                onClick={handleAddColumnClick}
                className="button is-primary"
                disabled={!(currentColumnSelection.category && currentColumnSelection.value)}
              >
                Add
              </button>
            </div>
          </div>
        </Card>
      )}
      <Card className="card">
        <header className="card-header">
          <p className="card-header-title">Selected Columns</p>
        </header>
        <div className="card-content">
          {columns.map(({ category, value }, i) => {
            let catStr = '';
            let valStr = '';
            try {
              catStr = columnOptions[row.category].find(col => col.value === category).label;
              valStr = columnValueOptions[row.category][category].find(colVal => colVal.value === value).label;
            } catch (e) {
              return undefined;
            }

            return (
              <Tag key={uuidv1()} className="tag is-primary is-medium">
                {`${catStr} - ${valStr}`}
                <button className="delete is-small" onClick={handleRemoveColumnClick(i)} />
              </Tag>
            );
          })}
        </div>
      </Card>
      <div id="options-container" style={{ display: 'flex' }}>
        <div style={{ marginRight: '1rem' }}>
          <button type="submit" className="button is-primary">
            {props.isSubmitting ? <FontAwesomeIcon icon={faSpinner} spin={true} /> : 'Generate'}
          </button>
        </div>
      </div>
    </form>
  );
}

interface FormProps {
  dispatch: () => any;
}

export default withFormik<FormProps, FieldValues>({
  handleSubmit: async (values, { props, setSubmitting }: any) => {
    const query = {
      from: format(values.from, 'YYYY-MM-DD'),
      to: format(values.to, 'YYYY-MM-DD'),
      row: values.row,
      columns: values.columns
    };

    const { data: results } = await axios.post('/api/report/generate', query);

    props.dispatch({
      type: ACTION_TYPES.SET_STATE,
      payload: {
        query,
        results
      }
    });

    setSubmitting(false);
  },
  mapPropsToValues: () => {
    const reqQuery = parseQueryStr(window.location.search, { arrayFormat: 'bracket' });

    const splitCatValToObj = catValStr => {
      const catVal = catValStr.split(',');
      return {
        category: catVal[0],
        value: catVal[1]
      };
    };

    const processReqQuery = givenReqQuery => {
      const result = {
        columns: [],
        row: {
          category: '' as RowCategory,
          value: ''
        },
        from: '' as string | Date,
        to: '' as string | Date
      };

      if (givenReqQuery.columns) {
        result.columns = givenReqQuery.columns.map(col => splitCatValToObj(col));
      }

      if (givenReqQuery.row) {
        result.row = splitCatValToObj(givenReqQuery.row);
      }

      if (givenReqQuery.from) {
        result.from = parseDateFns(givenReqQuery.from);
      }

      if (givenReqQuery.to) {
        result.to = parseDateFns(givenReqQuery.to);
      }

      return {
        ...givenReqQuery,
        ...result
      };
    };

    const initialState = {
      currentColumnSelection: {
        category: '' as ColumnCategory,
        value: ''
      },
      ...processReqQuery(reqQuery)
    };

    return initialState;
  },
  validationSchema: Yup.object().shape({
    from: Yup.date().required('Start date is required.'),
    to: Yup.date().required('End date is required.'),
    row: Yup.object().shape({
      category: Yup.string().required('Row category is required'),
      value: Yup.string().required('Row value is required')
    })
  })
})(Settings);
