import React from 'react';

/* eslint-disable jsx-a11y/click-events-have-key-events */
const DialogCell = props => {
  const style = {
    ...props.style,
    border: props.isSelected && '2px solid',
    borderColor: props.isSelected && props.hasError && 'red'
  };

  return (
    <td style={{ ...style }} onClick={props.clickHandler}>
      <div
        style={{
          whiteSpace: 'nowrap',
          overflow: 'hidden',
          textOverflow: 'ellipsis'
        }}
        className={props.contentClassName}
      >
        {props.value}
      </div>
    </td>
  );
};
/* eslint-enable jsx-a11y/click-events-have-key-events */

export default DialogCell;
