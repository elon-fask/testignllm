export function selectTestSession(testSessionId, selectedTestSessionIds) {
  return selectedTestSessionIds.includes(testSessionId)
    ? selectedTestSessionIds
    : [...selectedTestSessionIds, testSessionId];
}

export function selectCandidate(candidate, selectedCandidates) {
  return {
    ...selectedCandidates,
    [candidate.id]: {
      ...candidate,
      amountToBePaid: candidate.amountDue
    }
  };
}

export function selectCandidateByTestSession(candidateId, testSessionId, selectedCandidatesByTestSession) {
  const currentTestSessionList = selectedCandidatesByTestSession[testSessionId];

  if (currentTestSessionList) {
    if (currentTestSessionList.includes(candidateId)) {
      return selectedCandidatesByTestSession;
    }
    return {
      ...selectedCandidatesByTestSession,
      [testSessionId]: [...currentTestSessionList, candidateId]
    };
  }

  return {
    ...selectedCandidatesByTestSession,
    [testSessionId]: [candidateId]
  };
}
