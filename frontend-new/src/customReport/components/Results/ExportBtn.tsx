import * as React from 'react';
import { createPortal } from 'react-dom';

const { useRef, useEffect } = React;

function Portal({ children }: { children: React.ReactNode }) {
  const el = useRef(document.createElement('div'));
  const portalRoot = useRef(document.getElementById('options-container'));

  useEffect(() => {
    portalRoot.current.appendChild(el.current);
    return () => {
      portalRoot.current.removeChild(el.current);
    };
  }, []);

  return createPortal(children, el.current);
}

function ExportBtn({ handleClick }: { handleClick: () => void }) {
  return (
    <Portal>
      <button type="button" className="button is-primary" onClick={handleClick}>
        Export to Excel
      </button>
    </Portal>
  );
}

export default ExportBtn;
