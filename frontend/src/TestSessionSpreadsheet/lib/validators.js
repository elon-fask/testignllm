import { COL_NUM_MAPPING } from './refs';

export const validate = (value, col) => {
  const nameValidator = name => /^\S.*, \S.*\S$/.test(name);
  const disabledValidator = () => true;

  switch (col) {
    case COL_NUM_MAPPING.name: {
      return nameValidator(value);
    }
    default: {
      return disabledValidator(value);
    }
  }
};

export const errorMessages = [
  {
    title: 'Error in name field',
    body:
      'Name field must be in the format "Last Name, First Name". Please make sure there are no starting or trailing spaces.'
  }
];
