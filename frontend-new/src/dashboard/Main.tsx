import * as React from 'react';
import Spinner from '../common/components/Spinner';

const { Suspense, lazy } = React as any;

const StudentApplicationSearch = lazy(() => import('./StudentApplicationSearch'));
const OngoingClassesWidget = lazy(() => import('./OngoingClassesWidget'));
const UpcomingClassesWidget = lazy(() => import('./UpcomingClassesWidget'));

export interface OngoingClass {
  id: number;
  name: string;
  location: string;
  numCandidates: number;
  staff: {
    instructor: string;
    practicalExaminer: string;
    proctor: string;
    testSiteCoordinator: string;
  };
  pendingTransactions: number;
}

export type MaterialsStatus = 'NOT_SENT' | 'SENT' | 'ARRIVED';

export interface UpcomingClass {
  id: number;
  name: string;
  location: string;
  numCandidates: number;
  staff: {
    instructor: string;
    practicalExaminer: string;
    proctor: string;
    testSiteCoordinator: string;
  };
  materialsStatus: MaterialsStatus;
}

interface MainProps {
  ongoingClasses: OngoingClass[];
  upcomingClasses: UpcomingClass[];
}

function Main({ ongoingClasses, upcomingClasses }: MainProps) {
  return (
    <Suspense fallback={<Spinner />} maxDuration={2000}>
      <div className="row">
        <StudentApplicationSearch />
      </div>
      <div className="row">
        <OngoingClassesWidget ongoingClasses={ongoingClasses} />
      </div>
      <div className="row">
        <UpcomingClassesWidget upcomingClasses={upcomingClasses} />
      </div>
    </Suspense>
  );
}

export default Main;
