export function checkIfNameMatches(name, nameFilter) {
  if (!nameFilter) {
    return true;
  }

  return name.toLowerCase().includes(nameFilter.toLowerCase());
}
