import { handleActions } from 'redux-actions';

export default function createReducers(actions) {
    const initialState = {
        isLoading: false,
        results: [],
        value: ''
    };

    return handleActions({
        [actions.setIsLoading]:
            (state, action) =>
            ({ ...state, isLoading: action.payload }),
        [actions.setResults]:
            (state, action) =>
                ({ ...state, results: action.payload }),
        [actions.setValue]:
            (state, action) =>
                ({ ...state, value: action.payload })
    }, initialState);
};