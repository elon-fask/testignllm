import * as React from 'react';
import styled from 'styled-components';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPowerOff } from '@fortawesome/free-solid-svg-icons/faPowerOff';

const Nav = styled.nav`
  background-color: #0471af;

  .navbar-brand .navbar-item {
    color: #fff;
    font-size: 18px;
  }

  .navbar-menu .navbar-item {
    color: #fff;
    font-size: 18px;
  }

  .navbar-menu .navbar-item:hover {
    background-color: #004990;
  }

  .navbar-menu .navbar-item .navbar-link {
    color: #fff;
    font-size: 18px;
  }

  .navbar-menu .navbar-item .navbar-link:hover {
    background-color: #004990;
  }

  .navbar-item.has-dropdown:hover > .navbar-link {
    background-color: #004990;
  }

  .navbar-link:not(.is-arrowless)::after {
    border-color: #fff;
  }

  .navbar-dropdown .navbar-subtitle {
    padding-left: 1rem;
    font-size: 14px;
    color: #004990;
  }

  .navbar-dropdown .navbar-item {
    padding-left: 1.5rem;
    font-size: 14px;
    color: #333;
  }

  .navbar-dropdown .navbar-item:hover {
    background-color: #0471af;
    color: #fcfcfc;
  }
`;

function NavBar() {
  return (
    <Nav className="navbar" role="navigation" aria-label="main navigation">
      <div className="container">
        <div className="navbar-brand">
          <a className="navbar-item" href="/admin">
            Crane Admin
          </a>
        </div>
        <div className="navbar-menu">
          <a className="navbar-item" href="/admin/home">
            Dashboard
          </a>
          <div className="navbar-item has-dropdown is-hoverable">
            <a className="navbar-link">Manage Sessions</a>
            <div className="navbar-dropdown">
              <p className="navbar-subtitle">Manage Sessions</p>
              <a className="navbar-item" href="/admin/testsession">
                Written &amp; Practical Sessions List
              </a>
              <a className="navbar-item" href="/admin/testsession/create?type=Mg==">
                Add New Written Session
              </a>
              <a className="navbar-item" href="/admin/testsession/create?type=MQ==">
                Add New Practical Session
              </a>
              <a className="navbar-item" href="/admin/testsession/photos">
                Session Photos
              </a>
              <hr className="navbar-divider" />
              <p className="navbar-subtitle">Manage Sites</p>
              <a className="navbar-item" href="/admin/testsite/written">
                Written Sites List
              </a>
              <a className="navbar-item" href="/admin/testsite/practical">
                Practical Sites List
              </a>
              <a className="navbar-item" href="/admin/testsite/create?type=Mg==">
                Add New Written Site
              </a>
              <a className="navbar-item" href="/admin/testsite/create?type=MQ==">
                Add New Practical Site
              </a>
            </div>
          </div>
          <a className="navbar-item" href="/admin/calendar">
            Calendar
          </a>
          <div className="navbar-item has-dropdown is-hoverable">
            <a className="navbar-link">Applications</a>
            <div className="navbar-dropdown">
              <p className="navbar-subtitle">Manage Students</p>
              <a className="navbar-item" href="/admin/candidates/create">
                Manually Enter New Application
              </a>
              <a className="navbar-item" href="/admin/candidates/bulk-register">
                Bulk Register Student Applications
              </a>
              <a className="navbar-item" href="/admin/candidates">
                Search for Existing Application
              </a>
              <a className="navbar-item" href="/admin/candidates/search">
                Advanced Candidate Search
              </a>
              <hr className="navbar-divider" />
              <p className="navbar-subtitle">Manage Applications</p>
              <a className="navbar-item" href="/admin/application">
                Application Program Type
              </a>
              <a className="navbar-item" href="/admin/application/wizard">
                Application Program Wizard
              </a>
              <a className="navbar-item" href="/admin/application/create">
                Create New Application Type
              </a>
            </div>
          </div>
          <div className="navbar-item has-dropdown is-hoverable">
            <a className="navbar-link">Info</a>
            <div className="navbar-dropdown">
              <p className="navbar-subtitle">Companies</p>
              <a className="navbar-item" href="/admin/company">
                Companies
              </a>
              <a className="navbar-item" href="/admin/company/transaction">
                Company Transactions
              </a>
              <hr className="navbar-divider" />
              <p className="navbar-subtitle">Cranes</p>
              <a className="navbar-item" href="/admin/cranes">
                Crane List
              </a>
              <hr className="navbar-divider" />
              <p className="navbar-subtitle">Reports</p>
              <a className="navbar-item" href="/admin/reports">
                Reports Generation
              </a>
              <a className="navbar-item" href="/admin/reports/custom">
                Custom Report Generator
              </a>
              <hr className="navbar-divider" />
              <p className="navbar-subtitle">Promos</p>
              <a className="navbar-item" href="/admin/promo">
                Promo List
              </a>
              <a className="navbar-item" href="/admin/promo/create">
                Create Promo
              </a>
              <hr className="navbar-divider" />
              <a className="navbar-item" href="/admin/travel-form">
                Travel Forms
              </a>
              <hr className="navbar-divider" />
              <a className="navbar-item" href="/admin/staff">
                User List
              </a>
              <hr className="navbar-divider" />
              <p className="navbar-subtitle">Contacts</p>
              <a className="navbar-item" href="/admin/contacts">
                Student/Company Contact Search
              </a>
              <a className="navbar-item" href="/admin/contacts/download-email">
                Download Candidate Email Addresses
              </a>
              <hr className="navbar-divider" />
              <a className="navbar-item" href="/admin/testsession/all-receipts">
                Receipts
              </a>
              <a className="navbar-item" target="_blank" href="/admin/home/policies">
                Policies &amp; Procedures
              </a>
              <a className="navbar-item" href="/admin/settings">
                Settings
              </a>
              <hr className="navbar-divider" />
              <a className="navbar-item" href="/admin/default/logout">
                Logout
              </a>
            </div>
          </div>
          <div className="navbar-item has-dropdown is-hoverable">
            <a className="navbar-link">Logs</a>
            <div className="navbar-dropdown">
              <a className="navbar-item" href="/admin/user-log">
                User Activity Logs
              </a>
            </div>
          </div>
        </div>
        <div className="navbar-end">
          <div className="navbar-item">
            <div className="buttons">
              <a className="button is-light" href="/admin/default/logout">
                <FontAwesomeIcon icon={faPowerOff} />
              </a>
            </div>
          </div>
        </div>
      </div>
    </Nav>
  );
}

export default NavBar;
