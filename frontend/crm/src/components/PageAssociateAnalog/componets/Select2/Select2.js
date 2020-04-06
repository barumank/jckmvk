import React from 'react';
import {Form} from 'semantic-ui-react';

const Select2 = (props) => {
    let {
        input: {value, ...input},
        search,
        selection,
        options,
        onSearchChange,
        disabled,
        loading,
        renderLabel,
        meta: {touched, invalid, error}
    } = props;

    let elementProp = {
        onClick: (event, data) => {
            event.preventDefault();
        },
        onSearchChange,
        search,
        selection,
        options,
        onChange: (event, data) => {
            event.preventDefault();
            input.onChange(data.value)
        },
        renderLabel,
        disabled,
        loading
    };
    if (invalid) {
        elementProp.error = error;
    }
    return (
        <Form.Dropdown
            {...elementProp}
        />
    )
};

export default Select2;