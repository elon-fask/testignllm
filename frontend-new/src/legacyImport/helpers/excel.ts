export function intToExcelCol(num) {
  let colName = '';
  let dividend = Math.floor(Math.abs(num));
  let rest = null;

  while (dividend > 0) {
    rest = (dividend - 1) % 26;
    colName = String.fromCharCode(65 + rest) + colName;
    dividend = parseInt(((dividend - rest) / 26).toString(), 10);
  }

  return colName;
}

export function excelColToInt(colName) {
  const digits = colName.toUpperCase().split('');
  let num = 0;

  for (let i = 0; i < digits.length; i++) {
    num += (digits[i].charCodeAt(0) - 64) * Math.pow(26, digits.length - i - 1);
  }

  return num;
}

export function convertDateExcel(excelTimestamp) {
  const secondsInDay = 24 * 60 * 60;
  const excelEpoch = new Date(1899, 11, 31);
  const excelEpochAsUnixTimestamp = excelEpoch.getTime();
  const missingLeapYearDay = secondsInDay * 1000;
  const delta = excelEpochAsUnixTimestamp - missingLeapYearDay;
  const excelTimestampAsUnixTimestamp = excelTimestamp * secondsInDay * 1000;
  const parsed = excelTimestampAsUnixTimestamp + delta;
  return isNaN(parsed) ? null : parsed;
}
