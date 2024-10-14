import React from 'react';

const ReadOnlyCell = props => {
  const style = {
    ...props.style
  };

  return (
    <td style={{ ...style }} className={props.className}>
      <div className={props.contentClassName}>{props.value}</div>
    </td>
  );
};

export default ReadOnlyCell;
