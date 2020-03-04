import React from 'react'
import style from "./SearchComponent.module.css";
import {Header, Segment, Search} from "semantic-ui-react";

const SearchComponent = (props)=>{

    const {header,loading,value,onSearchChange,placeholder} = props;
    let currentPlaceholder = (placeholder === null || placeholder === undefined) ? 'Поиск...' :placeholder;

    return (
        <Segment className={style.searchContainer}>
            <Header as='h4'>{header}</Header>
            <Search
                className={style.searchInput}
                loading={loading}
                placeholder={currentPlaceholder}
                open={false}
                /*onResultSelect={this.handleResultSelect}*/
                onSearchChange={onSearchChange}
                value={value}
            />
        </Segment>
    );
};

export default SearchComponent;