import * as React from 'react';
import { createGlobalStyle } from 'styled-components';
import 'bulma/css/bulma.min.css';
import NavBar from '../navBar';
import Footer from '../footer';

const PageStyle = createGlobalStyle`
html {
  background-color: rgb(252, 252, 252);
}

html,
body {
 height: 100%;
 position: relative;
}

.main-container {
 min-height: 100vh;
 overflow: hidden;
 display: block;
 position: relative;
 padding-bottom: 340px;
}

.input.is-success, .textarea.is-success {
  border-color: #3c763d;
}

.help.is-success {
  color: #3c763d;
}

.input.is-danger, .textarea.is-danger {
  border-color: #a94442;
}

.help.is-danger {
  color: #a94442;
}

.button.is-primary {
  background-color: #337ab7;
}

.button.is-primary.is-hovered, .button.is-primary:hover {
  background-color: #286090;
}

.button.is-primary[disabled] {
  background-color: #286090;
}

.tag:not(body).is-primary {
  background-color: #337ab7;
}
`;

interface AppProps {
  children: React.ReactNode;
}

function App(props: AppProps) {
  return (
    <div className="main-container">
      <PageStyle />
      <NavBar />
      {props.children}
      <Footer />
    </div>
  );
}

export default App;
