import { keysFormFields } from './applicationForms';

export const gradeValues = {
  '0': 'Fail',
  '1': 'Pass',
  '2': 'Did Not Test',
  '3': 'SD'
};

export const gradeColors = {
  '0': 'red',
  '1': 'green',
  '2': 'orange',
  '3': 'red'
};

export const gradeLabelValues = {
  Fail: '0',
  Pass: '1',
  'Did Not Test': '2',
  SD: '3'
};

export const baseGrades = {
  W_EXAM_CORE: '--',
  W_EXAM_TLL: '--',
  W_EXAM_TSS: '--',
  P_TELESCOPIC_TLL: '--',
  P_TELESCOPIC_TSS: '--'
};

export const testNames = {
  W_EXAM_CORE: 'Core Exam (Written)',
  W_EXAM_LBC: 'Written LBC',
  W_EXAM_LBT: 'Written LBT',
  W_EXAM_BTF: 'Written BTF',
  W_EXAM_TOWER: 'Written Tower',
  W_EXAM_OVERHEAD: 'Written Overhead',
  W_EXAM_TLL: 'Written SW',
  W_EXAM_TSS: 'Written FX',
  P_LATTICE: 'Practical Lattice',
  P_TOWER: 'Practical Tower',
  P_OVERHEAD: 'Practical Overhead',
  P_TELESCOPIC_TLL: 'Practical SW',
  P_TELESCOPIC_TSS: 'Practical FX'
};

export const gradeableFields = [
  'W_EXAM_CORE',
  'W_EXAM_LBC',
  'W_EXAM_LBT',
  'W_EXAM_BTF',
  'W_EXAM_TOWER',
  'W_EXAM_OVERHEAD',
  'W_EXAM_TLL',
  'W_EXAM_TSS',
  'W_EXAM_ADD_LBC',
  'W_EXAM_ADD_LBT',
  'W_EXAM_ADD_TLL',
  'W_EXAM_ADD_BTF',
  'W_EXAM_ADD_TOWER',
  'W_EXAM_ADD_OVERHEAD',
  'P_LATTICE',
  'P_TOWER',
  'P_OVERHEAD',
  'P_TELESCOPIC_TLL',
  'P_TELESCOPIC_TSS'
];

export const applicationFormsToGrades = applicationForms =>
  Object.keys(applicationForms).reduce((acc, field) => {
    try {
      const convertedField = keysFormFields[field];
      const validField = typeof convertedField !== 'undefined';
      const enabledField = applicationForms[field];
      const gradeableField = gradeableFields.includes(convertedField);

      if (validField && enabledField && gradeableField) {
        return [...acc, convertedField];
      }
    } catch (e) {
      return acc;
    }
    return acc;
  }, []);

export const parseGrades = grades =>
  grades.reduce((acc, grade) => {
    const gradeAcc = grade.results.reduce(
      (resultAcc, result) => ({
        ...resultAcc,
        [result.key]: gradeValues[result.val]
      }),
      {}
    );
    return {
      ...acc,
      ...gradeAcc
    };
  }, {});
