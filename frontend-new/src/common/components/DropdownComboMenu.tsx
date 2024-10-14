import * as React from 'react';
import styled from 'styled-components';

const DropdownMenuElement = styled.div`
  & ul.dropdown-menu > li > button {
    width: 100%;
    text-align: left;
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
    background: none;
    border: none;
  }

  & ul.dropdown-menu > li > button:hover {
    color: #fcfcfc;
    background: #0471af;
    transition: all 0.3s ease;
  }
`;

interface DropdownComboMenuProps {
  dropdownLabel: string;
  dropdownOptionLabels: {
    [key: string]: string;
  };
  dropdownOptions: string[];
  handleDropdownChange: (value: string) => void;
  handleTextInputChange: (value: string) => void;
  id: string;
  label: string;
  value: string;
}

function DropdownComboMenu(props: DropdownComboMenuProps) {
  const handleDropdownChange = (e: React.MouseEvent<HTMLButtonElement>) => {
    const newDropdownValue = e.currentTarget.dataset.value;
    props.handleDropdownChange(newDropdownValue);
  };

  const handleTextInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const newValue = e.currentTarget.value;
    props.handleTextInputChange(newValue);
  };

  return (
    <React.Fragment>
      <label htmlFor={props.id}>{props.label}</label>
      <DropdownMenuElement className="form-group input-group">
        <input
          type="text"
          className="form-control"
          id={props.id}
          placeholder=""
          value={props.value}
          onChange={handleTextInputChange}
        />
        <div className="input-group-btn">
          <button
            type="button"
            className="btn btn-default dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="true"
          >
            {props.dropdownLabel}&nbsp;
            <span className="caret" />
          </button>
          <ul className="dropdown-menu dropdown-menu-right">
            {props.dropdownOptions.map(option => (
              <li key={option}>
                <button type="button" onClick={handleDropdownChange} data-value={option}>
                  {props.dropdownOptionLabels[option]}
                </button>
              </li>
            ))}
          </ul>
        </div>
      </DropdownMenuElement>
    </React.Fragment>
  );
}

export default DropdownComboMenu;
