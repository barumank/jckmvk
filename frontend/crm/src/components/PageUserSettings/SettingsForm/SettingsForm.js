import React from 'react'
import {Field, reduxForm, SubmissionError} from "redux-form";
import {Button, Form, Grid, Image, Message} from "semantic-ui-react";
import {connect} from "react-redux";
import style from './SettingsForm.module.css';
import Input from "../../components/FormElements/Input";
import FileInput from "../../components/FormElements/File";
import {setCurrentUser} from "../../components/Auth/reducer";

let SettingsForm = (props) => {

    const {image,userId, handleSubmit, pristine, reset, submitting, error} = props;

    const onButtonClick = (e) => {
        e.preventDefault();
        document.getElementById('userImage').click()
    };

    const onFileChange = (file)=>{
        let reader = new FileReader();
        reader.onloadend = () => {
            document.getElementById('userLogo')
                .setAttribute('src',reader.result)
        };
        reader.readAsDataURL(file)
    };

    const getUserImage = () =>{
        if(image === null){
            return (<Image id='userLogo' src='/img/no_image.png'/>)
        }
        return  <Image id='userLogo' src={`/upload/users/${userId}/${image}`} />
    };

    return (
        <Form onSubmit={handleSubmit} loading={submitting} error={!!error}>
            <Grid columns='two' divided>
                <Grid.Row>
                    <Grid.Column width='4' className={style.logoColumn}>
                        <div>
                        { getUserImage() }
                        </div>
                        <Button onClick={onButtonClick} className={style.uploadPhoto}>Загрузить фото</Button>
                        <Field
                            component={FileInput}
                            name="image"
                            id='userImage'
                            onChange={onFileChange}
                            style={{display:'none'}}
                            accept=".jpg,.png,.jpeg,.bmp"
                        />
                    </Grid.Column>
                    <Grid.Column width='8'>
                        <Field
                            name="id"
                            type="hidden"
                            component='input'
                        />
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
                            name="last_name"
                            component={Input}
                            label='Фамилия'
                            type="text"
                            placeholder="Фамилия"
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
                        <Button positive disabled={submitting} >Сохранить</Button>
                    </Grid.Column>
                </Grid.Row>
            </Grid>
        </Form>
    );
};

SettingsForm = reduxForm({
    form: 'settingsForm'
})(SettingsForm);

export default connect(null,(dispatch)=>({
    onSubmit(values){
        return appApi.user.saveSettings(values).then((response) => {
            if (response.status === 'ok') {
                dispatch(setCurrentUser(response.data.user));
                return;
            }
            throw new SubmissionError({
                _error: response.error,
                ...response.errors
            })
        });
    }
}))(SettingsForm);
