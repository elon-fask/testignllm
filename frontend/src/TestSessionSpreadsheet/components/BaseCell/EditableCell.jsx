import React from 'react';
import TextField from './TextField';

/* eslint-disable jsx-a11y/click-events-have-key-events */
const EditableCell = props => {
  const style = {
    ...props.style,
    border: props.isSelected && '2px solid',
    borderColor: props.isSelected && props.hasError && 'red'
  };

  return (
    <td style={{ ...style }} onClick={props.clickHandler} className={props.className}>
      <div
        style={{
          whiteSpace: 'nowrap',
          overflow: 'hidden',
          textOverflow: 'ellipsis'
        }}
        className={props.contentClassName}
      >
        {props.isSelected ? (
          <TextField
            initialValue={props.value}
            cancelHandler={props.cancelHandler}
            blurHandler={props.blurHandler}
            hasError={props.hasError}
          />
        ) : (
          props.value
        )}
      </div>
    </td>
  );
};
/* eslint-enable jsx-a11y/click-events-have-key-events */

export default EditableCell;
