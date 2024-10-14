export type SearchTypes = 'COMBINED' | 'NAME' | 'PHONE' | 'COMPANY' | 'INVOICE_NUMBER' | 'PO_NUMBER';
export const SEARCH_TYPES: SearchTypes[] = ['COMBINED', 'NAME', 'PHONE', 'COMPANY', 'INVOICE_NUMBER', 'PO_NUMBER'];

export function checkSearchType(searchType: string): SearchTypes {
  if (searchType === 'COMBINED') {
    return 'COMBINED';
  }

  if (searchType === 'NAME') {
    return 'NAME';
  }

  if (searchType === 'PHONE') {
    return 'PHONE';
  }

  if (searchType === 'COMPANY') {
    return 'COMPANY';
  }

  if (searchType === 'INVOICE_NUMBER') {
    return 'INVOICE_NUMBER';
  }

  if (searchType === 'PO_NUMBER') {
    return 'PO_NUMBER';
  }

  throw new Error('Invalid search type.');
}

export function generateUrlQuery(searchTerm: string, searchType: SearchTypes) {
  if (searchType === 'COMBINED') {
    return `query=${encodeURI(searchTerm)}&combinedQuery=1`;
  }

  if (searchType === 'NAME') {
    const studentNameArr = searchTerm.split(', ');

    if (studentNameArr[1]) {
      return `CandidatesSearch%5Blast_name%5D=${encodeURI(
        studentNameArr[0]
      )}&CandidatesSearch%5Bfirst_name%5D=${encodeURI(studentNameArr[1])}`;
    }

    return `CandidatesSearch%5Blast_name%5D=${encodeURI(studentNameArr[0])}`;
  }

  if (searchType === 'PHONE') {
    const encodedPhone = encodeURI(searchTerm);

    const homePhoneQuery = 'CandidatesSearch%5Bphone%5D=' + encodedPhone;
    const cellPhoneQuery = 'CandidatesSearch%5BcellNumber%5D=' + encodedPhone;
    const companyPhoneQuery = 'CandidatesSearch%5Bcompany_phone%5D=' + encodedPhone;
    const faxNumberQuery = 'CandidatesSearch%5BfaxNumber%5D=' + encodedPhone;

    return `${homePhoneQuery}&${cellPhoneQuery}&${companyPhoneQuery}&${faxNumberQuery}`;
  }

  if (searchType === 'COMPANY') {
    return `CandidatesSearch%5Bcompany_name%5D=${encodeURI(searchTerm)}`;
  }

  if (searchType === 'PO_NUMBER') {
    return `CandidatesSearch%5Bpurchase_order_number%5D=${encodeURI(searchTerm)}`;
  }

  if (searchType === 'INVOICE_NUMBER') {
    return `CandidatesSearch%5Binvoice_number%5D=${encodeURI(searchTerm)}`;
  }
}

type SearchTypesKeyedObject = { [key in SearchTypes]: string };

export const optionLabels: SearchTypesKeyedObject = {
  COMBINED: 'Combined',
  NAME: 'Name (Last Name, First Name)',
  PHONE: 'Phone Number',
  COMPANY: 'Company Name',
  INVOICE_NUMBER: 'Invoice Number',
  PO_NUMBER: 'Purchase Order (PO) Number'
};

export const optionText: SearchTypesKeyedObject = {
  COMBINED: 'Combined',
  NAME: 'Name',
  PHONE: 'Phone Number',
  COMPANY: 'Company Name',
  INVOICE_NUMBER: 'Invoice Number',
  PO_NUMBER: 'PO Number'
};
