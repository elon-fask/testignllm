import * as React from 'react';

const { createContext, useReducer } = React;

const initialState: any = {
  query: null,
  results: null
};

export enum ACTION_TYPES {
  SET_STATE = 'SET_STATE',
  SET_QUERY = 'SET_QUERY',
  SET_RESULTS = 'SET_RESULTS'
}

function reducer(state: any, action: any) {
  switch (action.type) {
    case ACTION_TYPES.SET_STATE: {
      return {
        ...state,
        ...action.payload
      };
    }
    default: {
      return state;
    }
  }
}

const MainContext = createContext(undefined as any);

const MainContextProvider = ({ children }: any) => {
  const [state, dispatch] = useReducer(reducer, initialState);
  const value = { state, dispatch };

  return <MainContext.Provider value={value}>{children}</MainContext.Provider>;
};

const MainContextConsumer = MainContext.Consumer;

export { MainContext, MainContextProvider, MainContextConsumer };
