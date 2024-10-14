import React from 'react';

const Cell = props => <td onClick={props.onClick}>{props.value}</td>;

export default Cell;
