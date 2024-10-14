import React, { Component } from 'react';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import Dialog from 'material-ui/Dialog';
import CircularProgress from 'material-ui/CircularProgress';
import BigCalendar from 'react-big-calendar';
import axios from 'axios';
import moment from 'moment';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import ModalContents from './ModalContents';
import '../styles/Calendar.css';

BigCalendar.setLocalizer(BigCalendar.momentLocalizer(moment));

const theme = getMuiTheme({
  palette: {
    accent1Color: '#0471af',
    primary1Color: '#0471af'
  }
});

export default class CalendarComponent extends Component {
  constructor() {
    super();
    this.state = {
      calendarDate: new Date(),
      isLoadingCalendar: true,
      isLoadingModalData: false,
      testSessionModalData: {},
      testSessionModalOpen: false,
      testSessions: []
    };
  }

  componentWillMount() {
    const today = new Date();
    this.getEvents(moment(today).subtract(1, 'M'), moment(today).add(2, 'M'));
  }

  getEvents = (startDate, endDate) => {
    this.setState({ isLoadingCalendar: true });
    axios
      .get(`/admin/calendar/events?start=${startDate.format('YYYY-MM-DD')}&end=${endDate.format('YYYY-MM-DD')}`)
      .then(response => {
        this.setState({
          testSessions: response.data.map(testSession => ({
            ...testSession,
            start: moment(testSession.start, 'YYYY-MM-DD').toDate(),
            end: moment(testSession.end, 'YYYY-MM-DD').toDate()
          })),
          isLoadingCalendar: false
        });
      })
      .catch(e => {
        console.log(e);
      });
  };

  selectEvent = ({ id }) => {
    this.setState({ isLoadingModalData: true });
    this.handleOpen();

    axios
      .get(`/admin/calendar/session-data-json?id=${id}`)
      .then(response => {
        this.setState({
          testSessionModalData: response.data,
          isLoadingModalData: false
        });
      })
      .catch(e => {
        console.log(e);
      });
  };

  handleCalendarNavigate = selectedDate => {
    this.setState({ calendarDate: selectedDate });
    this.getEvents(moment(selectedDate).subtract(1, 'M'), moment(selectedDate).add(2, 'M'));
  };

  handleOpen = () => {
    this.setState({ testSessionModalOpen: true });
  };

  handleClose = () => {
    this.setState({ testSessionModalOpen: false });
  };

  render() {
    return (
      <MuiThemeProvider muiTheme={theme}>
        <div>
          <div
            style={{
              height: '80vh',
              display: 'flex',
              justifyContent: 'center',
              alignItems: 'center'
            }}
          >
            {this.state.isLoadingCalendar ? (
              <CircularProgress />
            ) : (
              <BigCalendar
                style={{ flexBasis: '100%' }}
                date={this.state.calendarDate}
                popup
                selectable
                events={this.state.testSessions}
                views={['month']}
                defaultView="month"
                onNavigate={this.handleCalendarNavigate}
                scrollToTime={new Date(2015, 1, 1, 1)}
                defaultDate={new Date()}
                onSelectEvent={this.selectEvent}
              />
            )}
          </div>
          <Dialog
            title="Session Information"
            modal={false}
            open={this.state.testSessionModalOpen}
            onRequestClose={this.handleClose}
            autoScrollBodyContent
          >
            <ModalContents
              isLoadingModalData={this.state.isLoadingModalData}
              testSessionData={this.state.testSessionModalData.testSessionInfo}
            />
          </Dialog>
        </div>
      </MuiThemeProvider>
    );
  }
}
