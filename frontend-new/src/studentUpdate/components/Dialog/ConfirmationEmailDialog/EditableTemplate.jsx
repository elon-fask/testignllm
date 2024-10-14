import React, { useState } from 'react';
import styled from 'styled-components';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit } from '@fortawesome/free-solid-svg-icons/faEdit';
import { faCheck } from '@fortawesome/free-solid-svg-icons/faCheck';

const DisplayText = styled.span`
  line-height: 26px;
  font-weight: 700;
  text-decoration: underline solid black;
  white-space: nowrap;
`;

const HoverContainer = styled.span`
  position: relative;
  display: flex-inline;
  align-items: center;

  &:hover {
    cursor: pointer;
  }

  & > button {
    padding: 1px 1px 2px 4px;
    margin-left: 2px;
    height: 26px;
    display: none;
    position: absolute;
    z-index: 1;
    top: 0;
    right: -24px;
  }

  &:hover > button {
    display: block;
  }
`;

function EditableTemplate(props) {
  const [isEditing, setIsEditing] = useState(false);
  const displayText = props.fixedLabel || props.field.value;

  if (isEditing) {
    return (
      <span style={{ position: 'relative' }}>
        <DisplayText>{displayText}</DisplayText>
        <span
          style={{
            position: 'absolute',
            top: '20px',
            left: '2px',
            padding: '12px',
            border: '1px solid #66afe9',
            backgroundColor: 'white',
            borderRadius: '4px',
            zIndex: 1,
            display: 'flex'
          }}
        >
          <div className="form-group" style={{ minWidth: '300px', marginBottom: 0, marginRight: '8px' }}>
            <input className="form-control" type={props.type || 'text'} {...props.field} />
          </div>
          <button
            type="button"
            className="btn btn-success"
            onClick={() => {
              setIsEditing(false);
            }}
          >
            <FontAwesomeIcon icon={faCheck} />
          </button>
        </span>
      </span>
    );
  }

  return (
    <HoverContainer>
      <DisplayText
        onClick={() => {
          setIsEditing(true);
        }}
      >
        {displayText}
      </DisplayText>
      <button
        type="button"
        className="btn btn-primary"
        onClick={() => {
          setIsEditing(true);
        }}
      >
        <FontAwesomeIcon icon={faEdit} />
      </button>
    </HoverContainer>
  );
}

export default EditableTemplate;
