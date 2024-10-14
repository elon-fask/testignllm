interface IOptions {
  startDate?: Date;
  endDate?: Date;
  minDate?: Date;
  maxDate?: Date;
  isRange?: boolean;
  allowSameDayRange?: boolean;
  disabledDates?: Date[];
  disabledWeekDays?: boolean;
  lang?: string;
  dateFormat?: string;
  displayMode?: string;
  showHeader?: true;
  showFooter?: true;
  todayButton?: true;
  clearButton?: true;
  cancelLabel?: string;
  clearLabel?: string;
  todayLabel?: string;
  nowLabel?: string;
  validateLabel?: string;
  labelFrom?: string;
  labelTo?: string;
  weekStart?: number;
  weekDaysFormat?: string;
  closeOnOverlayClick?: boolean;
  closeOnSelect?: boolean;
  toggleOnInputClick?: boolean;
  icons?: {
    previous?: string;
    next?: string;
  };
}

declare module 'bulma-calendar' {
  export default class bulmaCalendar {
    constructor(selector: string | HTMLElement, options?: IOptions);

    static attach: (selector: string | HTMLElement, options?: IOptions) => bulmaCalendar;
  }
}
