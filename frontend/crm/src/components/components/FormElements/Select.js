import React from 'react'
import {Form} from 'semantic-ui-react'

const Select = (props) => {

    let {input: {value, ...input}, options, label, type, placeholder, meta: {touched, invalid, error}} = props;

    let elementProp = {
        ...input,
        value,
        onChange: (event, data) => input.onChange(data.value),
        type,
        placeholder,
        label,
        options,
    };
    if (invalid) {
        elementProp.error = error;
    }
    return (
        <Form.Select {...elementProp} />
    )
};

export default Select;
