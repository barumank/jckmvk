import React from 'react'
import {Dropdown} from "semantic-ui-react";

const WrappedDropdown = (props) => {
    const { options, onChange} = props;
    const handleOnClick = (event) => {
        event.preventDefault();
    };
    return (
        <Dropdown
            placeholder=''
            fluid
            selection
            options={options}
            onChange={onChange}
            onClick={handleOnClick}
        />
    )
};

export default WrappedDropdown;