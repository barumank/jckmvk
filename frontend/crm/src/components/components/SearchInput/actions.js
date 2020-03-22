import { createAction } from 'redux-actions';


export default function createActions(namespace, selectors) {

    /**
     * Action types.
     */
    const SEARCH_INPUT_LOADING = `${namespace}_SEARCH_INPUT_LOADING`;

    const SEARCH_INPUT_RESULTS = `${namespace}_SEARCH_INPUT_RESULTS`;

    const SEARCH_INPUT_VALUE = `${namespace}_SEARCH_INPUT_VALUE`;

    /**
     * Action creators.
     */
    const setIsLoading = createAction(SEARCH_INPUT_LOADING);

    const setResults = createAction(SEARCH_INPUT_RESULTS);

    const setValue = createAction(SEARCH_INPUT_VALUE);

    return {
        SEARCH_INPUT_LOADING: SEARCH_INPUT_LOADING,
        setIsLoading,
        SEARCH_INPUT_RESULTS: SEARCH_INPUT_RESULTS,
        setResults,
        SEARCH_INPUT_VALUE: SEARCH_INPUT_VALUE,
        setValue
    };
}