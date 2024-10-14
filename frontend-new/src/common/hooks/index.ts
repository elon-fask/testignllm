import { useEffect, useRef } from 'react';

/**
 * Accepts a function that contains imperative, possibly effectful code. Runs only after initial render.
 *
 * @param effect Imperative function that can return a cleanup function
 * @param watchList If present, effect will only activate if the values in the list change.
 *
 */
export function useEffectDidUpdate(effect: React.EffectCallback, watchList: ReadonlyArray<any>) {
  const hasRenderedOnce = useRef(false);

  useEffect(() => {
    if (hasRenderedOnce.current) {
      effect();
    } else {
      hasRenderedOnce.current = true;
    }
  }, watchList);
}
