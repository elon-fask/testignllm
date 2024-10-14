import * as React from 'react';
import styled from 'styled-components';

const FooterStyled = styled.footer`
  & {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    background: #a1a1a1;
    color: #fcfcfc;
  }

  & > .container {
    padding: 24px;
  }

  & a {
    font-size: 10px;
    color: #545a5f;
  }

  & a:hover {
    color: #fcfcfc;
  }

  & h4 > a {
    font-size: 14px;
  }
`;

const CopyrightSection = styled.section`
  padding: 18px;
  background-color: #545a5f;
  color: #e1e3e5;
  font-size: 10px;
`;

function Footer() {
  return (
    <FooterStyled>
      <div className="container">
        <div className="columns">
          <section className="column">
            <ul>
              <li>
                <h4>
                  <a href="/admin/home">Dashboard</a>
                </h4>
              </li>
              <li>
                <h4>
                  <a href="/admin/messaging">Inbox</a>
                </h4>
              </li>
              <li>
                <h4>
                  <a href="/admin/calendar">Calendar</a>
                </h4>
              </li>
            </ul>
          </section>
          <section className="column">
            <ul>
              <li>
                <h4>
                  <a href="#">Sites &amp; Sessions</a>
                </h4>
              </li>
              <li>
                <a href="#">Manage Sessions</a>
              </li>
              <li>
                <a href="/admin/testsession">Written &amp; Practical Sessions List</a>
              </li>
              <li>
                <a href="/admin/testsession/create?type=Mg==">Add New Written Session</a>
              </li>
              <li>
                <a href="/admin/testsession/create?type=MQ==">Add New Practical Session</a>
              </li>
              <li>
                <a href="#">Manage Sites</a>
              </li>
              <li>
                <a href="/admin/testsite/written">Written Sites List</a>
              </li>
              <li>
                <a href="/admin/testsite/practical">Practical Sites List</a>
              </li>
              <li>
                <a href="/admin/testsite/create?type=Mg==">Add New Written Site</a>
              </li>
              <li>
                <a href="/admin/testsite/create?type=MQ==">Add New Practical Site</a>
              </li>
            </ul>
          </section>
          <section className="column">
            <ul>
              <li>
                <h4>
                  <a href="#">Applications</a>
                </h4>
              </li>
              <li>
                <a href="#">Manage Students</a>
              </li>
              <li>
                <a href="/admin/candidates/create">Manually Enter New Application</a>
              </li>
              <li>
                <a href="/admin/candidates">Search for Existing Application</a>
              </li>
              <li>
                <a href="#">Manage Applications</a>
              </li>
              <li>
                <a href="/admin/application">Application Program Type</a>
              </li>
              <li>
                <a href="/admin/application/create">Create New Application Type</a>
              </li>
            </ul>
          </section>
          <section className="column">
            <ul>
              <li>
                <h4>
                  <a href="#">Staff</a>
                </h4>
              </li>
              <li>
                <a href="/admin/staff">Staff List</a>
              </li>
              <li>
                <a href="/admin/user">Website Admin</a>
              </li>
              <li>
                <h4>
                  <a href="#">Promos</a>
                </h4>
              </li>
              <li>
                <a href="/admin/promo">Promo List</a>
              </li>
              <li>
                <a href="/admin/promo/create">Create Promo</a>
              </li>
            </ul>
          </section>
          <section className="column">
            <ul>
              <li>
                <h4>
                  <a href="/admin/default/logout">Logout</a>
                </h4>
              </li>
            </ul>
          </section>
          <section className="column">
            <img src="/images/ccslogo-s.png" />
          </section>
        </div>
      </div>
      <CopyrightSection>
        <div className="container">
          <span>©</span> craneadmin.com 2019
        </div>
      </CopyrightSection>
    </FooterStyled>
  );
}

export default Footer;
