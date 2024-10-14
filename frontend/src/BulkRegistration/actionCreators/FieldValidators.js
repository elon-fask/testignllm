const checkIfBlank = value =>
  value ? { isValid: true, error: '' } : { isValid: false, error: 'Field must not be blank' };

const checkIfUppercaseLetter = value => {
  if (/^[A-Z]+$/.test(value)) {
    return { isValid: true, error: '' };
  }
  return {
    isValid: false,
    error: 'Field be an uppercase letter value corresponding to a column name in the Excel file.'
  };
};

const fieldValidations = {
  selectedFileName: (value, { file }) => {
    const filenameArr = file.name.split('.');
    const fileExtension = filenameArr[-1];

    if (!file) {
      return {
        isValid: false,
        error: 'Please select an Excel file (.xls, .xlsx)'
      };
    }

    if (fileExtension === 'xls' || fileExtension === '.xlsx') {
      return {
        isValid: false,
        error: 'File must be in Excel format (.xls, .xlsx)'
      };
    }

    return { isValid: true, error: '' };
  },
  selectedApplicationTypeId: checkIfBlank,
  selectedPromoCodeId: checkIfBlank,
  selectedTestSiteId: checkIfBlank,
  selectedTestSessionId: checkIfBlank,
  startingRowValue: value => {
    if (!/^\d+$/.test(value)) {
      return { isValid: false, error: 'Field must be a number' };
    }

    return { isValid: true, error: '' };
  },
  endingRowValue: value => {
    if (!/^\d+$/.test(value)) {
      return { isValid: false, error: 'Field must be a number' };
    }

    return { isValid: true, error: '' };
  },
  columnLastName: checkIfUppercaseLetter,
  columnFirstName: checkIfUppercaseLetter,
  columnEmail: checkIfUppercaseLetter,
  columnPhone: checkIfUppercaseLetter,
  columnBirthday: checkIfUppercaseLetter,
  columnAddress: checkIfUppercaseLetter,
  columnCity: checkIfUppercaseLetter,
  columnState: checkIfUppercaseLetter,
  columnZip: checkIfUppercaseLetter
};

const validate = (fieldName, value, details) => {
  if (typeof fieldValidations[fieldName] === 'undefined') {
    return { isValid: true, error: '' };
  }

  return fieldValidations[fieldName](value, details);
};

export default validate;
