import axios from 'axios';
import moment from 'moment';
import _filter from 'lodash/filter';
import _find from 'lodash/find';
import _findIndex from 'lodash/findIndex';
import isEmail from 'validator/lib/isEmail';
import validate from './FieldValidators';

const prepareTable = (tablePreview, columnMappings) => {
  const columnValidate = (type, value) => {
    switch (type) {
      case 'EMAIL': {
        if (isEmail(value)) {
          return {
            isValid: true
          };
        }
        return { isValid: false, message: `Invalid email address ${value}.` };
      }
      case 'PHONE': {
        if (/^\(\d{3}\) \d{3}-\d{4}$/.test(value)) {
          return { isValid: true };
        }
        return {
          isValid: false,
          message: `Invalid phone number ${value}. Please use the format (123) 456-7890`
        };
      }
      case 'BIRTHDAY': {
        if (moment(value).isValid()) {
          return { isValid: true };
        }
        return {
          isValid: false,
          message: `Invalid date ${value}. Please use the format 01/20/1990 (MM/DD/YYYY) in the Excel file.`
        };
      }
      case 'ZIP': {
        if (/^\d{5}$/.test(value)) {
          return { isValid: true };
        }
        return {
          isValid: false,
          message: `Invalid Zip code ${value}.`
        };
      }
      case 'STATE': {
        if (/^\w{2}$/.test(value)) {
          return { isValid: true };
        }
        return {
          isValid: false,
          message: `Invalid state ${value}. Please use the two-letter code for the state column in the Excel file.`
        };
      }
      default: {
        return { isValid: true };
      }
    }
  };

  const errors = [];

  tablePreview.forEach(row => {
    row.forEach(column => {
      const result = columnValidate(
        typeof columnMappings[column.column] !== 'undefined' ? columnMappings[column.column] : '',
        column.value
      );
      if (!result.isValid) {
        errors.push(result.message);
      }
    });
  });

  return errors;
};

const previewUpload = (file, formValues, dispatch) => {
  const formData = new FormData();
  formData.append(0, file);

  axios
    .post('preview-bulk-register', formData)
    .then(({ data }) => {
      const filteredTable = _filter(data.table, row => row.A);

      const tablePreview = Object.keys(filteredTable)
        .map(key => parseInt(key, 10))
        .sort((a, b) => (a > b ? 1 : -1))
        .map(row =>
          Object.keys(filteredTable[row]).map(column => {
            return {
              column,
              value: filteredTable[row][column]
            };
          })
        );

      const startingIndex = _findIndex(tablePreview, row => _find(row, { column: 'A' }).value === 'Last Name') + 1;

      const tablePreviewSanitized = tablePreview.map((row, i) => {
        if (i < startingIndex) {
          return row;
        }

        return row.map(cell => {
          if (cell.column === formValues.columnPhone) {
            if (!cell.value) {
              return {
                ...cell,
                value: ''
              };
            }

            const phoneNumberRaw = cell.value.replace(/\D/g, '');

            if (phoneNumberRaw.length < 9) {
              return {
                ...cell,
                value: cell.value
              };
            }

            const sanitizedPhoneNumber = `(${phoneNumberRaw.slice(0, 3)}) ${phoneNumberRaw.slice(
              3,
              6
            )}-${phoneNumberRaw.slice(6, 10)}`;

            return {
              ...cell,
              value: sanitizedPhoneNumber
            };
          }

          return cell;
        });
      });

      dispatch({
        type: 'SET_TABLE_PREVIEW_DATA',
        highestColumn: data.highestColumn,
        tablePreview: tablePreviewSanitized
      });

      const errors = prepareTable(tablePreviewSanitized.slice(startingIndex), {
        [formValues.columnEmail]: 'EMAIL',
        [formValues.columnPhone]: 'PHONE',
        [formValues.columnBirthday]: 'BIRTHDAY',
        [formValues.columnZip]: 'ZIP',
        [formValues.columnState]: 'STATE'
      });

      dispatch({
        type: 'SET_TABLE_PREVIEW_ERRORS',
        errors
      });

      if (errors.length > 0) {
        dispatch({
          type: 'SHOW_TABLE_PREVIEW_ERRORS_MODAL'
        });
      }

      dispatch({
        type: 'UPDATE_FORM_VALUE',
        fieldName: 'startingRowValue',
        value: (startingIndex + 1).toString()
      });

      dispatch({
        type: 'UPDATE_FORM_VALUE',
        fieldName: 'endingRowValue',
        value: tablePreviewSanitized.length.toString()
      });
    })
    .catch(e => {
      console.error(e);
    });
};

export const closeModal = () => ({
  type: 'CLOSE_MODAL'
});

export const updateFormValue = (fieldName, value, details) => (dispatch, getState) => {
  const { isValid, error } = validate(fieldName, value, details);

  if (isValid) {
    dispatch({
      type: 'UPDATE_ERROR_VALUE',
      fieldName,
      value: ''
    });
  } else {
    dispatch({
      type: 'UPDATE_ERROR_VALUE',
      fieldName,
      value: error
    });
  }

  dispatch({
    type: 'UPDATE_FORM_VALUE',
    fieldName,
    value
  });

  if (isValid && fieldName === 'selectedFileName') {
    dispatch({
      type: 'START_LOADING_TABLE_PREVIEW'
    });
    previewUpload(details.file, getState().ui.formValues, dispatch);
  }
};

const validateAllFields = (dispatch, formValues, tablePreview) => {
  const selectedFile = document.getElementById('file-input').files
    ? document.getElementById('file-input').files[0]
    : false;

  const hasErrors = Object.keys(formValues).reduce((acc, key) => {
    const details = {};
    if (key === 'selectedFileName') {
      details.file = selectedFile;
    }

    if (key === 'startingRowValue') {
      details.endingRowValue = formValues.endingRowValue;
      details.tableLength = tablePreview ? tablePreview.length : 0;
    }

    if (key === 'endingRowValue') {
      details.startingRowValue = formValues.startingRowValue;
      details.tableLength = tablePreview ? tablePreview.length : 0;
    }

    const { isValid, error } = validate(key, formValues[key], details);

    if (!isValid) {
      dispatch({
        type: 'UPDATE_ERROR_VALUE',
        fieldName: key,
        value: error
      });

      return true;
    }

    return acc || false;
  }, false);

  return hasErrors;
};

export const uploadCandidateInfo = () => (dispatch, getState) => {
  const { promoCodes, ui: { formValues, table: { tablePreview } } } = getState();

  const hasErrors = validateAllFields(dispatch, formValues, tablePreview);

  if (hasErrors) {
    return;
  }

  dispatch({
    type: 'START_UPLOADING_STUDENT_INFO'
  });
  dispatch({
    type: 'OPEN_UPLOAD_WAIT_PROMPT'
  });

  const {
    columnLastName,
    columnFirstName,
    columnEmail,
    columnPhone,
    columnBirthday,
    columnAddress,
    columnCity,
    columnState,
    columnZip,
    columnCompany
  } = formValues;

  const studentInfoArr = tablePreview.slice(parseInt(formValues.startingRowValue, 10) - 1).map(studentInfo => {
    let companyName = formValues.company;

    if (!companyName && columnCompany) {
      companyName = _find(studentInfo, { column: columnCompany }).value;
    }

    return {
      Candidates: {
        last_name: _find(studentInfo, { column: columnLastName }).value,
        first_name: _find(studentInfo, { column: columnFirstName }).value,
        email: _find(studentInfo, { column: columnEmail }).value,
        phone: _find(studentInfo, { column: columnPhone }).value,
        birthday: moment(_find(studentInfo, { column: columnBirthday }).value).format('MM/DD/YYYY'),
        address: _find(studentInfo, { column: columnAddress }).value,
        city: _find(studentInfo, { column: columnCity }).value,
        state: _find(studentInfo, { column: columnState }).value,
        zip: _find(studentInfo, { column: columnZip }).value.toString(),
        company_name: companyName,
        purchase_order_number: formValues.poNumber
      }
    };
  });

  const payload = {
    application_type_id: formValues.selectedApplicationTypeId,
    promo_code: promoCodes[formValues.selectedPromoCodeId].code,
    send_notification_email: formValues.emailStudentsChecked ? '1' : '0',
    test_session_id: formValues.selectedTestSessionId,
    student_info: studentInfoArr
  };

  axios
    .post('bulk-register', payload)
    .then(({ data }) => {
      dispatch({
        type: 'STOP_UPLOADING_STUDENT_INFO',
        zipUrl: data.zipUrl
      });
    })
    .catch(() => {
      dispatch({
        type: 'CLOSE_UPLOAD_WAIT_PROMPT'
      });
    });
};

export const closeUploadWaitPrompt = () => ({
  type: 'CLOSE_UPLOAD_WAIT_PROMPT'
});
