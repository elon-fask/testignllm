import React from 'react';

const Header = () => {
  const branding = 'ACS';

  return (
    <nav className="navbar navbar-fixed-top">
      <a className="navbar-brand" href="/">
        <img src="/images/site/acs/logo-sm.png" alt="ACS Logo" />
        American Crane School
      </a>
      <a className="navbar-brand" href="tel://8889577277">
        (888) 957-7277
      </a>
    </nav>
  );
};
export default Header;
