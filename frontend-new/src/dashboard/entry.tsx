import * as React from 'react';
import { render } from 'react-dom';
import Main, { OngoingClass, UpcomingClass } from './Main';

declare const ongoingClasses: OngoingClass[];
declare const upcomingClasses: UpcomingClass[];

render(
  <Main ongoingClasses={ongoingClasses} upcomingClasses={upcomingClasses} />,
  document.getElementById('react-entry')
);
