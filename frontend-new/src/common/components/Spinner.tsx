import * as React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSpinner } from '@fortawesome/free-solid-svg-icons/faSpinner';

function Spinner() {
  return (
    <div>
      <FontAwesomeIcon icon={faSpinner} spin={true} size="3x" />
    </div>
  );
}

export default Spinner;
