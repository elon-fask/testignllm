export const formFieldKeys = {
  W_EXAM_CORE: 'coreEnabled',
  W_EXAM_LBC: 'writtenLBCEnabled',
  W_EXAM_LBT: 'writtenLBTEnabled',
  W_EXAM_BTF: 'writtenBTFEnabled',
  W_EXAM_TOWER: 'writtenTowerEnabled',
  W_EXAM_OVERHEAD: 'writtenOverheadEnabled',
  W_EXAM_TLL: 'writtenSWEnabled',
  W_EXAM_TSS: 'writtenFXEnabled',
  W_EXAM_ADD_LBC: 'writtenAddLBCEnabled',
  W_EXAM_ADD_LBT: 'writtenAddLBTEnabled',
  W_EXAM_ADD_TLL: 'writtenAddSWEnabled',
  W_EXAM_ADD_BTF: 'writtenAddBTFEnabled',
  W_EXAM_ADD_TOWER: 'writtenAddTowerEnabled',
  W_EXAM_ADD_OVERHEAD: 'writtenAddOverheadEnabled',
  P_LATTICE: 'practicalLatticeEnabled',
  P_TOWER: 'practicalTowerEnabled',
  P_OVERHEAD: 'practicalOverheadEnabled',
  P_TELESCOPIC_TLL: 'practicalSWEnabled',
  P_TELESCOPIC_TSS: 'practicalFXEnabled',
  W_FEE_LATE: 'lateFeeEnabled',
  W_FEE_INCOMPLETE: 'incompleteFeeEnabled'
};

export const keysFormFields = {
  coreEnabled: 'W_EXAM_CORE',
  writtenLBCEnabled: 'W_EXAM_LBC',
  writtenLBTEnabled: 'W_EXAM_LBT',
  writtenBTFEnabled: 'W_EXAM_BTF',
  writtenTowerEnabled: 'W_EXAM_TOWER',
  writtenOverheadEnabled: 'W_EXAM_OVERHEAD',
  writtenSWEnabled: 'W_EXAM_TLL',
  writtenFXEnabled: 'W_EXAM_TSS',
  writtenAddLBCEnabled: 'W_EXAM_ADD_LBC',
  writtenAddLBTEnabled: 'W_EXAM_ADD_LBT',
  writtenAddSWEnabled: 'W_EXAM_ADD_TLL',
  writtenAddBTFEnabled: 'W_EXAM_ADD_BTF',
  writtenAddTowerEnabled: 'W_EXAM_ADD_TOWER',
  writtenAddOverheadEnabled: 'W_EXAM_ADD_OVERHEAD',
  practicalLatticeEnabled: 'P_LATTICE',
  practicalTowerEnabled: 'P_TOWER',
  practicalOverheadEnabled: 'P_OVERHEAD',
  practicalSWEnabled: 'P_TELESCOPIC_TLL',
  practicalFXEnabled: 'P_TELESCOPIC_TSS',
  lateFeeEnabled: 'W_FEE_LATE',
  incompleteFeeEnabled: 'W_FEE_INCOMPLETE'
};

const writtenTestKeys = [
  'coreEnabled',
  'writtenLBCEnabled',
  'writtenLBTEnabled',
  'writtenBTFEnabled',
  'writtenTowerEnabled',
  'writtenOverheadEnabled',
  'writtenSWEnabled',
  'writtenFXEnabled',
  'writtenAddLBCEnabled',
  'writtenAddLBTEnabled',
  'writtenAddSWEnabled',
  'writtenAddBTFEnabled',
  'writtenAddTowerEnabled',
  'writtenAddOverheadEnabled'
];

const practicalTestKeys = [
  'practicalLatticeEnabled',
  'practicalTowerEnabled',
  'practicalOverheadEnabled',
  'practicalSWEnabled',
  'practicalFXEnabled'
];

export const formFieldText = {
  W_EXAM_CORE: 'Mobile Core Exam',
  W_EXAM_LBC: 'Lattice Boom Crawler (LBC)',
  W_EXAM_LBT: 'Lattice Boom Truck (LBT)',
  W_EXAM_BTF: 'Boom Truck-Fixed Cab (BTF)',
  W_EXAM_TOWER: 'Tower Crane',
  W_EXAM_OVERHEAD: 'Overhead Crane',
  W_EXAM_TLL: 'Telescopic Boom-Swing Cab (TLL)',
  W_EXAM_TSS: 'Telescopic Boom-Fixed Cab (TSS)',
  W_EXAM_ADD_LBC: 'Lattice Boom Crawler (LBC) (Recert Additional)',
  W_EXAM_ADD_LBT: 'Lattice Boom Truck (LBT) (Recert Additional)',
  W_EXAM_ADD_TLL: 'Telescopic Boom-Swing Cab (TLL)',
  W_EXAM_ADD_TSS: 'Telescopic Boom-Fixed Cab (TSS)',
  W_EXAM_ADD_BTF: 'Boom Truck-Fixed Cab (BTF)',
  W_EXAM_ADD_TOWER: 'Tower Crane',
  W_EXAM_ADD_OVERHEAD: 'Overhead Crane',
  P_LATTICE: 'Lattice Boom Crane',
  P_TOWER: 'Tower Crane',
  P_OVERHEAD: 'Overhead Crane',
  P_TELESCOPIC_TLL: 'Telescopic Boom Crane - Swing Cab (TLL)',
  P_TELESCOPIC_TSS: 'Telescopic Boom Crane - Fixed Cab (TSS)'
};

export const formFeeFieldsRecert = {
  W_FEE_CORE_1: 150,
  W_FEE_CORE_2: 155,
  W_FEE_CORE_3: 160,
  W_FEE_CORE_4: 165,
  W_FEE_TOWER: 150,
  W_FEE_TOWER_W_MOBILE: 50,
  W_FEE_OVERHEAD: 150,
  W_FEE_OVERHEAD_W_MOBILE: 50,
  W_FEE_RETEST_CORE_1: 150,
  W_FEE_RETEST_SPECIALTY_1: 50,
  W_FEE_RETEST_SPECIALTY_2: 55,
  W_FEE_RETEST_SPECIALTY_3: 60,
  W_FEE_RETEST_SPECIALTY_4: 65,
  W_FEE_ADDED_SPECIALTY_1: 65,
  W_FEE_ADDED_SPECIALTY_2: 75,
  W_FEE_ADDED_SPECIALTY_3: 85,
  W_FEE_ADDED_TOWER: 50,
  W_FEE_ADDED_OVERHEAD: 50,
  W_FEE_LATE: 50,
  W_FEE_INCOMPLETE: 30
};

export const formFeeFieldsCert = {
  W_FEE_LATE: 50,
  W_FEE_INCOMPLETE: 30,
  W_FEE_UPDATE_REPLACE: 25,
  W_FEE_CORE_1: 165,
  W_FEE_CORE_2: 175,
  W_FEE_CORE_3: 185,
  W_FEE_CORE_4: 195,
  W_FEE_ADDED_CORE: 165,
  W_FEE_ADDED_SPECIALTY_1: 65,
  W_FEE_ADDED_SPECIALTY_2: 75,
  W_FEE_ADDED_SPECIALTY_3: 85,
  W_FEE_ADDED_SPECIALTY_4: 95,
  W_FEE_TOWER_NEW: 165,
  W_FEE_TOWER_CURRENT: 50,
  W_FEE_OVERHEAD_NEW: 165,
  W_FEE_OVERHEAD_CURRENT: 50
};

export const getCheckedFees = (applicationForm, isRecert) => {
  const fields = Object.keys(applicationForm);
  const fees = isRecert ? formFeeFieldsRecert : formFeeFieldsCert;
  return fields.reduce((acc, field) => (fees[field] && applicationForm[field] === 'on' ? [...acc, field] : acc), []);
};

export const getFeeTotal = (checkedFees, isRecert, excludeOtherFees = false) => {
  const feesTable = isRecert ? formFeeFieldsRecert : formFeeFieldsCert;

  const otherFees = ['W_FEE_LATE', 'W_FEE_INCOMPLETE', 'W_FEE_UPDATE_REPLACE'];

  return checkedFees.reduce((acc, checkedFee) => {
    if (otherFees.includes(checkedFee) && excludeOtherFees) {
      return acc;
    }
    return acc + (feesTable[checkedFee] || 0);
  }, 0);
};

export const convertCustomFormSetupToArray = customFormSetupStr => {
  const customFormSetup = JSON.parse(customFormSetupStr) || {};

  return Object.keys(customFormSetup).map(appForm => ({
    form_name: appForm,
    form_setup: customFormSetup[appForm]
  }));
};

export const parseApplicationForms = applicationForms => {
  const applicationFormsMerged = applicationForms.reduce(
    (applicationFormsAcc, applicationForm) => ({
      ...applicationFormsAcc,
      ...(typeof applicationForm.form_setup === 'string'
        ? JSON.parse(applicationForm.form_setup)
        : applicationForm.form_setup)
    }),
    {}
  );

  const isRecert = !!applicationForms.find(
    applicationForm => applicationForm.form_name === 'iai-blank-recert-with-1000-hours-application'
  );

  const formSetup = {
    coreEnabled: applicationFormsMerged.W_EXAM_CORE === 'on',
    writtenSWEnabled: applicationFormsMerged.W_EXAM_TLL === 'on',
    writtenFXEnabled: applicationFormsMerged.W_EXAM_TSS === 'on',
    practicalSWEnabled: applicationFormsMerged.P_TELESCOPIC_TLL === 'on',
    practicalFXEnabled: applicationFormsMerged.P_TELESCOPIC_TSS === 'on',
    lateFeeEnabled: applicationFormsMerged.W_FEE_LATE === 'on',
    incompleteFeeEnabled: applicationFormsMerged.W_FEE_INCOMPLETE === 'on'
  };

  const checkedFees = getCheckedFees(applicationFormsMerged, isRecert);
  const totalFees = getFeeTotal(checkedFees, isRecert);

  return { formSetup, checkedFees, totalFees, applicationFormsMerged };
};

export const parseFormSetup = formSetupMerged => {
  const result = {};

  const fields = Object.keys(formSetupMerged);

  fields.forEach(field => {
    if (typeof formFieldKeys[field] !== 'undefined') {
      result[formFieldKeys[field]] = formSetupMerged[field] === 'on';
    }
  });

  return result;
};

export const checkIfHasWritten = formSetupMerged =>
  Object.keys(formSetupMerged).reduce((acc, key) => {
    if (writtenTestKeys.includes(key) && formSetupMerged[key]) {
      return true;
    }
    return acc;
  }, false);

export const checkIfHasPractical = formSetupMerged =>
  Object.keys(formSetupMerged).reduce((acc, key) => {
    if (practicalTestKeys.includes(key) && formSetupMerged[key]) {
      return true;
    }
    return acc;
  }, false);

export const checkIfHasWrittenAndPractical = formSetupMerged =>
  checkIfHasWritten(formSetupMerged) && checkIfHasPractical(formSetupMerged);

export const checkIfPracticalOnly = formSetupMerged => {
  const practicalEnabled = Object.keys(formSetupMerged).reduce((acc, key) => {
    if (acc || (practicalTestKeys.includes(key) && formSetupMerged[key])) {
      return true;
    }
    return false;
  }, false);

  const noWritten = !Object.keys(formSetupMerged).reduce((acc, key) => {
    if (acc || (writtenTestKeys.includes(key) && formSetupMerged[key])) {
      return true;
    }
    return false;
  }, false);

  return practicalEnabled && noWritten;
};
