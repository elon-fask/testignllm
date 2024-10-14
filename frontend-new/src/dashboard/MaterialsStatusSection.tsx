import * as React from 'react';
import axios from 'axios';
import { withFormik, Field, FormikProps } from 'formik';
import { MaterialsStatus } from './Main';
import { materialsStatusStr } from '../common/materialsStatus';
import SelectField from '../common/components/formik/bootstrap/SelectField';
import TextField from '../common/components/formik/bootstrap/TextField';

const { useState } = React;

const statusStyle = (status: MaterialsStatus) => {
  if (status === 'NOT_SENT') {
    return {
      fontWeight: 700,
      color: 'red'
    };
  }

  return {
    fontWeight: 700,
    color: 'green'
  };
};

interface MaterialsStatusSectionValues {
  status: MaterialsStatus;
  trackingNo?: string;
}

function MaterialsStatusSection(props: FormikProps<MaterialsStatusSectionValues>) {
  const [settingsVisible, setVisibility] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setVisibility(false);
    props.handleSubmit();
  };

  return (
    <div>
      {!settingsVisible && (
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          <div style={statusStyle(props.values.status)}>{materialsStatusStr[props.values.status]}</div>
          <button
            type="button"
            className="btn btn-primary"
            onClick={() => {
              setVisibility(true);
            }}
          >
            Update Status
          </button>
        </div>
      )}
      {settingsVisible && (
        <form onSubmit={handleSubmit}>
          <Field name="status" label="Status" component={SelectField} options={materialsStatusStr} />
          <Field name="trackingNo" label="Tracking Number" component={TextField} />
          <button type="submit" className="btn btn-primary">
            Confirm Update
          </button>
        </form>
      )}
    </div>
  );
}

interface MaterialsStatusFormProps {
  id: number;
  materialsStatus: MaterialsStatus;
  materialsTrackingNo?: string;
}

export default withFormik({
  mapPropsToValues: (props: MaterialsStatusFormProps) => {
    return {
      status: props.materialsStatus,
      trackingNo: props.materialsTrackingNo
    };
  },
  handleSubmit: async (values, { props }) => {
    if (values.trackingNo) {
      return axios.post(
        `/admin/testsession/update-materials-status?id=${props.id}&status=${values.status}&trackingNo=${
          values.trackingNo
        }`
      );
    }

    return axios.post(`/admin/testsession/update-materials-status?id=${props.id}&status=${values.status}`);
  }
})(MaterialsStatusSection);
