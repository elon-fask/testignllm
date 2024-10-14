import * as React from 'react';
import { MainContext } from './context';
import Spinner from '../common/components/Spinner';

const { Suspense, lazy, Fragment, useContext } = React;

const Settings = lazy(() => import('./components/Settings'));
const Results = lazy(() => import('./components/Results'));

function Main() {
  const { state, dispatch } = useContext(MainContext);

  return (
    <Fragment>
      <Suspense fallback={<Spinner />}>
        <section className="section">
          <Settings dispatch={dispatch} />
          {state.results && <Results query={state.query} results={state.results} />}
        </section>
      </Suspense>
    </Fragment>
  );
}

export default Main;
