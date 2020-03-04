import React from 'react'
import {Form} from 'semantic-ui-react'

const Input = (props) => {

    const {input, label, type,placeholder, meta: {touched, invalid, error}} = props;

    let elementProp = {
        ...input,
        type,
        placeholder,
        label,
    };
    if (invalid) {
        elementProp.error = error;
    }
    return (
        <Form.Input
            {...elementProp}
        />
    )
};

export default Input;
