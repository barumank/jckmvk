import React from 'react'
import {Dropdown} from "semantic-ui-react";

const WrappedDropdown = (props) => {
    const {
        options,
        input: {value, ...input},
        meta: {touched, invalid, error}
    } = props;
    let elementProp = {
        placeholder: '',
        fluid: true,
        selection: true,
        options,
        onChange: (event, data) => {
            event.preventDefault();
            input.onChange(data.value)
        },
        onClick: (event, data) => {
            event.preventDefault();
        },
    };
    if (invalid) {
        elementProp.error = error;
    }
    return (
        <Dropdown
            {...elementProp}
        />
    )
};

export default WrappedDropdown;