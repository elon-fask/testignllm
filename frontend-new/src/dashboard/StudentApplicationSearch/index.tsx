import * as React from 'react';
import { SEARCH_TYPES, checkSearchType, generateUrlQuery, optionLabels, optionText } from './helpers';
import DropdownComboMenu from '../../common/components/DropdownComboMenu';

function StudentApplicationSearch() {
  const [searchTypeStr, setSearchType] = React.useState('NAME');
  const [searchTerm, setSearchTerm] = React.useState('');

  const searchType = checkSearchType(searchTypeStr);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const searchQuery = generateUrlQuery(searchTerm, searchType);
    window.location.href = '/admin/candidates?' + searchQuery;
  };

  return (
    <div className="row dashboard-widgets">
      <form className="col-xs-12 col-md-6" onSubmit={handleSubmit}>
        <DropdownComboMenu
          id="student-name-new"
          value={searchTerm}
          handleTextInputChange={setSearchTerm}
          handleDropdownChange={setSearchType}
          label="Search for Student Application"
          dropdownLabel={`Search by: ${optionText[searchType]}`}
          dropdownOptions={SEARCH_TYPES}
          dropdownOptionLabels={optionLabels}
        />
        <button type="submit" className="btn btn-primary">
          Search
        </button>
      </form>
    </div>
  );
}

export default StudentApplicationSearch;
