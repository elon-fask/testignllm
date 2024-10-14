import axios from 'axios';
import React, { Component } from 'react';
import { withFormik, Field } from 'formik';
import Yup from 'yup';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import Paper from 'material-ui/Paper';
import RaisedButton from 'material-ui/RaisedButton';
import Checkbox from 'material-ui/Checkbox';
import { staffTypeMapping, staffRoles, staffRoleDescription } from '../common/staff';
import AutoComplete from '../common/components/formik/AutoComplete';
import TextField from '../common/components/formik/TextField';

const theme = getMuiTheme({
  palette: {
    primary1Color: '#0471af',
    accent1Color: '#0471af'
  }
});

class App extends Component {
  handleRoleCheckboxUpdate = (e, isChecked) => {
    const role = e.currentTarget.dataset.role;

    let newRoles = null;
    if (isChecked) {
      newRoles = [...this.props.values.roles, role];
    } else {
      newRoles = this.props.values.roles.filter(currentRole => currentRole !== role);
    }

    this.props.setFieldValue('roles', newRoles);
  };

  render() {
    const { props } = this;
    const { primaryUser, secondaryUser } = props;
    const firstNameSuggestions = [primaryUser.first_name, secondaryUser.first_name];
    const lastNameSuggestions = [primaryUser.last_name, secondaryUser.last_name];
    const emailSuggestions = [primaryUser.email, secondaryUser.email];
    const homePhoneSuggestions = [primaryUser.homePhone, secondaryUser.homePhone];
    const cellPhoneSuggestions = [primaryUser.cellPhone, secondaryUser.cellPhone];
    const workPhoneSuggestions = [primaryUser.workPhone, secondaryUser.workPhone];
    const faxSuggestions = [primaryUser.fax, secondaryUser.fax];
    const usernameSuggestions = [primaryUser.username, secondaryUser.username];
    return (
      <MuiThemeProvider muiTheme={theme}>
        <div>
          <Paper style={{ padding: '20px' }} zDepth={1}>
            <form onSubmit={props.handleSubmit} style={{ display: 'flex' }}>
              <div style={{ display: 'flex', flexDirection: 'column', marginRight: '20px' }}>
                <Field
                  name="firstName"
                  label="First Name*"
                  dataSource={firstNameSuggestions}
                  component={AutoComplete}
                />
                <Field name="lastName" label="Last Name*" dataSource={lastNameSuggestions} component={AutoComplete} />
                <Field name="email" label="Email*" dataSource={emailSuggestions} component={AutoComplete} />
                <Field name="homePhone" label="Home Phone" dataSource={homePhoneSuggestions} component={AutoComplete} />
                <Field name="cellPhone" label="Cell Phone" dataSource={cellPhoneSuggestions} component={AutoComplete} />
                <Field name="workPhone" label="Work Phone" dataSource={workPhoneSuggestions} component={AutoComplete} />
                <Field name="fax" label="Fax" dataSource={faxSuggestions} component={AutoComplete} />
                <hr />
                <Field name="username" label="Username*" dataSource={usernameSuggestions} component={AutoComplete} />
                <Field name="password" label="Password*" type="password" component={TextField} />
                <hr />
                <div>
                  <RaisedButton label="Save" primary onClick={props.handleSubmit} />
                </div>
              </div>
              <div>
                <div>Roles</div>
                {staffRoles.map(role => (
                  <Checkbox
                    key={role}
                    label={staffRoleDescription[role]}
                    checked={props.values.roles.includes(role)}
                    data-role={role}
                    onCheck={this.handleRoleCheckboxUpdate}
                  />
                ))}
              </div>
            </form>
          </Paper>
        </div>
      </MuiThemeProvider>
    );
  }
}

export default withFormik({
  mapPropsToValues: ({ primaryUser, secondaryUser }) => {
    const roles = [...primaryUser.roles, ...secondaryUser.roles];

    return {
      firstName: primaryUser.first_name || secondaryUser.first_name || '',
      lastName: primaryUser.last_name || secondaryUser.last_name || '',
      email: primaryUser.email || secondaryUser.email || '',
      homePhone: primaryUser.homePhone || secondaryUser.homePhone || '',
      cellPhone: primaryUser.cellPhone || secondaryUser.cellPhone || '',
      workPhone: primaryUser.workPhone || secondaryUser.workPhone || '',
      fax: primaryUser.fax || secondaryUser.fax || '',
      username: primaryUser.username || secondaryUser.username || '',
      password: '',
      roles
    };
  },
  handleSubmit: (values, { props }) => {
    axios
      .post(`/admin/staff/merge?primary=${props.primaryUser.id}&secondary=${props.secondaryUser.id}`, {
        firstName: values.firstName,
        lastName: values.lastName,
        email: values.email,
        homePhone: values.homePhone,
        cellPhone: values.cellPhone,
        workPhone: values.workPhone,
        fax: values.fax,
        username: values.username,
        password: values.password,
        roles: values.roles
      })
      .then(() => {
        window.location.href = '/admin/staff';
      });
  },
  validationSchema: Yup.object().shape({
    firstName: Yup.string().required('First Name is required.'),
    lastName: Yup.string().required('Last Name is required.'),
    email: Yup.string()
      .email('Must be a valid email address')
      .required('Email is required.'),
    homePhone: Yup.string(),
    cellPhone: Yup.string(),
    workPhone: Yup.string(),
    fax: Yup.string(),
    username: Yup.string().required('Username is required.'),
    password: Yup.string()
      .min(6, 'At least 6 characters is required.')
      .required('Password is required.')
  })
})(App);
