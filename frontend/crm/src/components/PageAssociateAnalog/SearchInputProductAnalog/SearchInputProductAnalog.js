import SearchInput from '../componets/SearchInput/SearchInput';
import { connect } from 'react-redux';
import {getIsLoading, getResults, getValue} from "./selectors"
import {onSearchChange, onResultSelect} from "./reducer"

export default connect(
    state => ({
        isLoading: getIsLoading(state),
        results: getResults(state),
        value: getValue(state),
    }),
    dispatch => ({
        onSearchChange: (event, data) => {
            dispatch(onSearchChange(event, data))
        },
        onResultSelect: (event, data) => {
            dispatch(onResultSelect(event, data))
        },
    }),
)(SearchInput);

