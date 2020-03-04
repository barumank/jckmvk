import React from 'react'
import {Button, Form, Message} from "semantic-ui-react";
import {Field, reduxForm, SubmissionError} from 'redux-form'
import {connect} from "react-redux";
import Input from "../../../components/FormElements/Input";
import {setCurrentUser} from "../../../components/Auth/reducer";

let LoginForm = (props) => {
    const {handleSubmit, pristine, reset, submitting, error} = props;
    return (
        <Form onSubmit={handleSubmit} loading={submitting} error={!!error} >
            <Field
                name="login"
                component={Input}
                label='Email'
                type="text"
                placeholder="Email"
            />
            <Field
                name="password"
                component={Input}
                label='Пароль'
                type="password"
                placeholder="Пароль"
            />

            {error && (<Message error content={error}/>)}
            <Button type='submit' disabled={submitting}>Войти</Button>
        </Form>
    );
};

LoginForm =  reduxForm({
    form: 'userLogin'
})(LoginForm);

export default connect(null, dispatch => ({
    onSubmit(values) {
        return appApi.auth.login(values).then((response)=>{
            if (response.status === 'ok') {
                dispatch(setCurrentUser(response.data.user));
                return;
            }
            throw new SubmissionError({
                _error: response.error,
            })
        });
    }
}))(LoginForm)
