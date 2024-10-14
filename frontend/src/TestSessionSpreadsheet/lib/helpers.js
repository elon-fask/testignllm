import {
  getCheckedFees as getCheckedFeesImport,
  getFeeTotal as getFeeTotalImport,
  checkIfHasWritten,
  checkIfHasWrittenAndPractical,
  checkIfPracticalOnly
} from '../../common/applicationForms';
import { baseGrades } from '../../common/grades';
import { sortPracticalTestSchedule, parsePracticeHours } from '../../common/practicalTestSchedule';

export function countCranes(state, fieldName) {
  return state.candidateIDs.reduce((acc, candidateID) => {
    const candidate = state.candidates[candidateID];
    const applicationType = state.applicationTypes[candidate.applicationTypeID];

    const mergedForms = {
      ...applicationType.formSetup,
      ...candidate.customFormSetup
    };

    return mergedForms[fieldName] ? acc + 1 : acc;
  }, 0);
}

export function countTotal(state, fieldName) {
  if (typeof fieldName === 'string') {
    return state.candidateIDs.reduce((acc, candidateID) => acc + (state.candidates[candidateID][fieldName] || 0), 0);
  }

  return state.candidateIDs.reduce(
    (acc, candidateID) => fieldName.map((fName, index) => acc[index] + (state.candidates[candidateID][fName] || 0)),
    fieldName.map(() => 0)
  );
}

export function countTotalTransactions(transactions, paymentChargeMapping) {
  return transactions.reduce(
    (acc, transaction) =>
      paymentChargeMapping.map(({ paymentType, chargeType }, index) => {
        if (transaction.paymentType === paymentType && (chargeType ? transaction.chargeType === chargeType : true)) {
          const netAmount = transactions.reduce((acc, t) => {
            if (t.paymentType === 20 && t.transaction_ref_id === transaction.id) {
              return acc - t.amount;
            }
            return acc;
          }, transaction.amount);
          return netAmount + acc[index];
        }
        return acc[index];
      }),
    paymentChargeMapping.map(() => 0)
  );
}

export const getCheckedFees = getCheckedFeesImport;

export const getFeeTotal = getFeeTotalImport;

export const splitCandidateState = state => {
  const { applicationTypes, candidateIDs, candidates } = state;

  return candidateIDs.reduce(
    (acc, candidateID) => {
      const candidate = candidates[candidateID];
      const applicationType = applicationTypes[candidate.applicationTypeID];
      const mergedFormSetup = { ...applicationType.formSetup, ...candidate.customFormSetup };

      if (checkIfPracticalOnly(mergedFormSetup)) {
        return [
          acc[0],
          acc[1],
          [...acc[2], candidateID],
          {
            ...acc[3],
            [candidateID]: candidate
          }
        ];
      }
      return [
        [...acc[0], candidateID],
        {
          ...acc[1],
          [candidateID]: candidate
        },
        acc[2],
        acc[3]
      ];
    },
    [[], {}, [], {}]
  );
};

export const filterCandidateState = (state, checker) => {
  const { applicationTypes, candidateIDs, candidates } = state;

  return candidateIDs.reduce(
    (acc, candidateID) => {
      const candidate = candidates[candidateID];
      const applicationType = applicationTypes[candidate.applicationTypeID];
      const mergedFormSetup = { ...applicationType.formSetup, ...candidate.customFormSetup };

      if (checker(mergedFormSetup)) {
        return [
          [...acc[0], candidateID],
          {
            ...acc[1],
            [candidateID]: candidate
          }
        ];
      }
      return [acc[0], acc[1]];
    },
    [[], {}]
  );
};

export const getWrittenCandidateState = state => filterCandidateState(state, checkIfHasWritten);
export const getPracticalCandidateState = state => filterCandidateState(state, checkIfHasWrittenAndPractical);
export const getPracticalOnlyCandidateState = state => filterCandidateState(state, checkIfPracticalOnly);

export const prepareMainTableData = state => {
  const numCoreExam = countCranes(state, 'coreEnabled');
  const numCranesWrittenSW = countCranes(state, 'writtenSWEnabled');
  const numCranesWrittenFX = countCranes(state, 'writtenFXEnabled');
  const numCranesPracticalSW = countCranes(state, 'practicalSWEnabled');
  const numCranesPracticalFX = countCranes(state, 'practicalFXEnabled');

  const enhancedCandidates = { ...state.candidates };
  const { practicalTestSchedule } = state.testSession;

  state.candidateIDs.forEach(candidateID => {
    const candidate = state.candidates[candidateID];
    const applicationType = state.applicationTypes[candidate.applicationTypeID];

    let practiceHours = null;

    if (candidate.practiceTimeCredits) {
      practiceHours = parsePracticeHours(candidate.practiceTimeCredits);
    }

    const practicalSchedule = practicalTestSchedule.find(({ candidate_id }) => candidate.id === candidate_id);

    if (practicalSchedule) {
      practiceHours = parsePracticeHours(practicalSchedule.practice_hours);
    }

    const grades = { ...baseGrades, ...candidate.grades };

    const mergedFormSetup = {
      ...applicationType.formSetup,
      ...candidate.customFormSetup
    };

    const checkedFees =
      candidate.customCheckedFees.length === 0 ? applicationType.checkedFees : candidate.customCheckedFees;
    let writtenCharges = getFeeTotal(checkedFees, applicationType.isRecert, true);
    if (typeof candidate.writtenNcccoFeeOverride !== 'undefined') {
      writtenCharges = candidate.writtenNcccoFeeOverride;
    }

    const hasApplicationCharge = candidate.transactions.reduce((transactionsAcc, transaction) => {
      const isBlankOrOtherChargeType = !transaction.chargeType || transaction.chargeType === 70;

      if (transaction.paymentType === 10 && isBlankOrOtherChargeType) {
        return true;
      }
      return transactionsAcc || false;
    }, false);

    const [
      charges,
      paymentCash,
      paymentCheck,
      paymentPromo,
      paymentElectronic,
      paymentIntuit,
      paymentOther,
      paymentSquare,
      refunds,
      chargeRemovals,
      chargeAdjustments
    ] = countTotalTransactions(candidate.transactions, [
      { paymentType: 10 },
      { paymentType: 1 },
      { paymentType: 2 },
      { paymentType: 3 },
      { paymentType: 4 },
      { paymentType: 5 },
      { paymentType: 6 },
      { paymentType: 7 },
      { paymentType: 20 },
      { paymentType: 30 },
      { paymentType: 31 }
    ]);

    const grossCustomerCharges = charges - chargeRemovals - chargeAdjustments;
    const customerCharges = grossCustomerCharges - refunds;
    const amountPaid =
      paymentCash + paymentCheck + paymentPromo + paymentElectronic + paymentIntuit + paymentOther + paymentSquare;

    const [lateFee, incompleteFee, walkInFee, otherFee, practiceTimeCharge] = countTotalTransactions(
      candidate.transactions,
      [
        { paymentType: 10, chargeType: 74 },
        { paymentType: 10, chargeType: 72 },
        { paymentType: 10, chargeType: 71 },
        { paymentType: 10, chargeType: 60 },
        { paymentType: 10, chargeType: 73 }
      ]
    );

    const { swRetest, fxRetest } = candidate.transactions.reduce(
      (acc, transaction) => {
        if (transaction.paymentType === 10 && transaction.chargeType === 50) {
          if (transaction.retest_crane_selection === 'sw') {
            return {
              ...acc,
              swRetest: acc.swRetest + 1
            };
          }

          if (transaction.retest_crane_selection === 'fx') {
            return {
              ...acc,
              fxRetest: acc.fxRetest + 1
            };
          }

          if (transaction.retest_crane_selection === 'both') {
            return {
              ...acc,
              swRetest: acc.swRetest + 1,
              fxRetest: acc.fxRetest + 1
            };
          }
        }
        return acc;
      },
      {
        swRetest: 0,
        fxRetest: 0
      }
    );

    const numCranesSWDeduction = grades.P_TELESCOPIC_TLL && grades.P_TELESCOPIC_TLL === 'Did Not Test' ? 1 : 0;
    const numCranesFXDeduction = grades.P_TELESCOPIC_TSS && grades.P_TELESCOPIC_TSS === 'Did Not Test' ? 1 : 0;

    const swTest = mergedFormSetup.practicalSWEnabled ? 1 : 0;
    const fxTest = mergedFormSetup.practicalFXEnabled ? 1 : 0;

    const owedPracticalSW = candidate.previousGrades.P_TELESCOPIC_TLL === 2 ? 1 : 0;
    const owedPracticalFX = candidate.previousGrades.P_TELESCOPIC_TSS === 2 ? 1 : 0;

    const numCranesSW =
      hasApplicationCharge || applicationType.price <= 0 ? swRetest + swTest : swRetest + owedPracticalSW;
    const numCranesFX =
      hasApplicationCharge || applicationType.price <= 0 ? fxRetest + fxTest : fxRetest + owedPracticalFX;

    let practicalCharges = '';

    const chargeableCranesSW = numCranesSW - numCranesSWDeduction;
    const chargeableCranesFX = numCranesFX - numCranesFXDeduction;

    const numCranesBothDiff = chargeableCranesSW - chargeableCranesFX;
    if (numCranesBothDiff === 0) {
      practicalCharges = 70 * chargeableCranesSW;
    }
    if (numCranesBothDiff > 0) {
      practicalCharges = 70 * chargeableCranesFX + 60 * (chargeableCranesSW - chargeableCranesFX);
    }
    if (numCranesBothDiff < 0) {
      practicalCharges = 70 * chargeableCranesSW + 60 * (chargeableCranesFX - chargeableCranesSW);
    }

    if (typeof candidate.practicalNcccoFeeOverride !== 'undefined') {
      practicalCharges = candidate.practicalNcccoFeeOverride;
    }

    const amountDue = grossCustomerCharges - amountPaid;

    let paymentStatus =
      candidate.isPurchaseOrder === 1 && candidate.isCompanySponsored === 1 ? 'Invoice' : 'Payment Due';

    if (candidate.invoiceNumber && candidate.isCompanySponsored === 1) {
      paymentStatus = 'Invoiced';
    }

    if (candidate.collectPaymentOverride) {
      paymentStatus = 'Payment Due';
    }

    if (amountDue === 0) {
      paymentStatus = 'Paid in Full';
    }

    enhancedCandidates[candidateID] = {
      ...enhancedCandidates[candidateID],
      practiceHours,
      writtenCharges,
      practicalCharges,
      mergedFormSetup,
      paymentStatus,
      customerCharges,
      amountPaid,
      amountDue,
      numCranesSW,
      numCranesFX,
      swRetest,
      fxRetest,
      lateFee,
      incompleteFee,
      walkInFee,
      otherFee,
      practiceTimeCharge,
      grades
    };
  });

  const [
    totalWrittenNcccoFees,
    totalPracticalCharges,
    totalCustomerCharges,
    totalPaid,
    totalDue,
    totalLateFee,
    totalIncompleteFee,
    totalWalkInFee,
    totalOtherFee,
    totalPracticeTimeCharges
  ] = countTotal({ candidateIDs: state.candidateIDs, candidates: enhancedCandidates }, [
    'writtenCharges',
    'practicalCharges',
    'customerCharges',
    'amountPaid',
    'amountDue',
    'practicalRetestFee',
    'lateFee',
    'incompleteFee',
    'walkInFee',
    'otherFee',
    'practiceTimeCharge'
  ]);

  let lessThanFee = 0;

  if (state.candidateIDs.length < 15) {
    lessThanFee = 200;
  }

  if (state.candidateIDs.length < 12) {
    lessThanFee = 300;
  }

  const ncccoTestFeesCredit = parseFloat(state.testSession.ncccoTestFeesCredit) || 0;

  const totalNcccoWrittenOtherFees = totalLateFee + totalIncompleteFee + totalWalkInFee + totalOtherFee;

  const totalNcccoWrittenFees =
    totalWrittenNcccoFees / 2 + totalNcccoWrittenOtherFees + lessThanFee - ncccoTestFeesCredit;

  const totalNcccoPracticalFees = totalPracticalCharges / 2;

  return {
    applicationTypeIDs: state.applicationTypeIDs,
    applicationTypes: state.applicationTypes,
    candidateIDs: state.candidateIDs,
    candidates: enhancedCandidates,
    numCoreExam,
    numCranesWrittenSW,
    numCranesWrittenFX,
    numCranesPracticalSW,
    numCranesPracticalFX,
    totalWrittenNcccoFees,
    totalPracticalCharges,
    totalCustomerCharges,
    totalPaid,
    totalDue,
    totalLateFee,
    totalIncompleteFee,
    totalWalkInFee,
    totalOtherFee,
    totalPracticeTimeCharges,
    lessThanFee,
    ncccoTestFeesCredit,
    totalNcccoWrittenOtherFees,
    totalNcccoWrittenFees,
    totalNcccoPracticalFees
  };
};

export const preparePracticalTestScheduleTableData = state => {
  const sortedPracticalTestSchedule = sortPracticalTestSchedule(state.testSession.practicalTestSchedule);

  const practicalCandidateIDs = sortedPracticalTestSchedule.map(({ id }) => id);

  const [practicalOnlyCandidateIds] = getPracticalOnlyCandidateState(state);

  const unscheduledCandidateIds = practicalOnlyCandidateIds.filter(
    id =>
      !sortedPracticalTestSchedule.reduce((acc, { candidate_id }) => {
        return acc || candidate_id === id;
      }, false)
  );

  const newCandidates = sortedPracticalTestSchedule.reduce((acc, { candidate_id, id }) => {
    const candidate = state.candidates[candidate_id];

    if (candidate) {
      const newCandidate = {
        ...candidate,
        testSchedule: state.testSession.practicalTestSchedule.find(schedule => schedule.id === id)
      };

      newCandidate.testSchedule.practiceTime = parsePracticeHours(newCandidate.testSchedule.practice_hours);

      return { ...acc, [id]: newCandidate };
    }

    return acc;
  }, {});

  const scrambledCandidateIds = [];

  unscheduledCandidateIds.forEach(id => {
    const candidate = state.candidates[id];
    candidate.testSchedule = {};
    candidate.testSchedule.practiceTime = '';

    const candidateKey = window.btoa(`${candidate.name}${candidate.id}`);
    scrambledCandidateIds.push(candidateKey);
    newCandidates[candidateKey] = candidate;
  });

  return {
    ...state,
    candidateIDs: [...scrambledCandidateIds, ...practicalCandidateIDs],
    candidates: newCandidates
  };
};
