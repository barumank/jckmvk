import { get } from 'lodash/fp';

export default function createSelectors(statePath, injectedSelectors = {}) {
    /**
     * Input selectors.
     */
    function isLoadingSelector(state) {
        return get(`${statePath}.isLoading`, state);
    }

    function getResultsSelector(state) {
        return get(`${statePath}.results`, state);
    }

    function getValueSelector(state) {
        return get(`${statePath}.value`, state);
    }

    return {
        isLoadingSelector,
        getResultsSelector,
        getValueSelector
    };
}