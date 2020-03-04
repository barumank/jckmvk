import React from 'react'
import {Button, Form, Message} from "semantic-ui-react";
import {Field, reduxForm, SubmissionError} from 'redux-form'
import {connect} from "react-redux";
import {setIsAcceptRegistration} from "../../reducer";
import Input from '../../../components/FormElements/Input'

let RegistrationForm = (props) => {
    const {handleSubmit, pristine, reset, submitting, error} = props;
    return (
        <Form onSubmit={handleSubmit} loading={submitting} error={!!error}>
            <Field
                name="email"
                component={Input}
                label='Email'
                type="text"
                placeholder="Email"
            />
            <Field
                name="name"
                component={Input}
                label='Имя'
                type="text"
                placeholder="Имя"
            />
            <Field
                name="password"
                component={Input}
                label='Пароль'
                type="password"
                placeholder="Пароль"
            />
            <Field
                name="confirm_password"
                component={Input}
                label='Повтарите пароль'
                type="password"
                placeholder="Повтарите пароль"
            />
            {error && (<Message error content={error}/>)}
            <Button type='submit' disabled={submitting}>Регистрация</Button>
        </Form>
    );
};

RegistrationForm = reduxForm({
    form: 'userRegistration'
})(RegistrationForm);

export default connect(null, dispatch => ({
    onSubmit(values) {
        return appApi.auth.registration(values).then((response) => {
            if (response.status === 'ok') {
                dispatch(setIsAcceptRegistration(true));
                return;
            }
            throw new SubmissionError({
                _error: response.error,
                ...response.errors
            })
        });
    }
}))(RegistrationForm)
