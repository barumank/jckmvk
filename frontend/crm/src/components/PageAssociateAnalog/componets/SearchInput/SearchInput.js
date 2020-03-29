import React from 'react'
import {Search} from "semantic-ui-react";
import {debounce} from "lodash";

const SearchInput = (props) => {
    const {
        isLoading,
        value,
        onSearchChange,
        onResultSelect,
        results
    } = props;


    return (
        <Search
            {...props.input}
            loading={isLoading}
            onResultSelect={onResultSelect}
            results={results}
            onSearchChange={debounce(onSearchChange, 500, {leading: true})}
            value={value}
        />
    )
};

export default SearchInput;