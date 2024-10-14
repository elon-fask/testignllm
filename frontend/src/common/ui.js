/* eslint-disable import/prefer-default-export */
export const parseOptions = options => {
  return Object.keys(options).reduce((acc, optionKey) => {
    if (optionKey === 'rowHeight') {
      try {
        return {
          ...acc,
          [optionKey]: parseInt(options[optionKey], 10)
        };
      } catch (e) {
        return acc;
      }
    }

    if (options[optionKey] === 'true') {
      return {
        ...acc,
        [optionKey]: true
      };
    }

    if (options[optionKey] === 'false') {
      return {
        ...acc,
        [optionKey]: false
      };
    }

    return acc;
  }, {});
};
