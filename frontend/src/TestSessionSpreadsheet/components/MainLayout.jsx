import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import React from 'react';
import '../styles/table.css';
import TableContainer from './TableContainer';
import Dialog from './Dialog';

const theme = getMuiTheme({
  palette: {
    accent1Color: '#0471af',
    primary1Color: '#0471af'
  }
});

const MainLayout = () => (
  <MuiThemeProvider muiTheme={theme}>
    <div>
      <TableContainer />
      <Dialog />
    </div>
  </MuiThemeProvider>
);

export default MainLayout;
