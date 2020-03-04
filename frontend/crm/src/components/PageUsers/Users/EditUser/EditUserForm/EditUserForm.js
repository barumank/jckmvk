import React from 'react'
import {Field, reduxForm, SubmissionError,change} from "redux-form";
import {Button, Form, Grid, Image, Message} from "semantic-ui-react";
import Input from "../../../../components/FormElements/Input";
import Select from "../../../../components/FormElements/Select";
import FileInput from "../../../../components/FormElements/File";
import {connect} from "react-redux";
import {modalClose, updateUsers} from "../reducer";
import style from './EditUserForm.module.css';

let EditUserForm = (props) => {

    const {userImage,userId, handleSubmit, pristine, reset, submitting, error} = props;

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
        if(userImage === null){
            return (<Image id='userLogo' src='/img/no_image.png'/>)
        }
        return  <Image id='userLogo' src={`/upload/users/${userId}/${userImage}`} />
    };

    return (
        <Form onSubmit={handleSubmit} loading={submitting} error={!!error}>
            <Grid columns='two' divided>
                <Grid.Row>
                    <Grid.Column width='5' className={style.logoColumn}>
                        { getUserImage() }
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
                    <Grid.Column width='11'>
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
                            options={appApi.user.ROLE_LIST.map((item)=>({ key: item.value, value: item.value, text: item.label }))}
                            name="role"
                            component={Select}
                            label='Роль'
                            type="text"
                            placeholder="Роль"
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
                    </Grid.Column>
                </Grid.Row>
            </Grid>
        </Form>
    );
};

EditUserForm = reduxForm({
    form: 'adminEditUserForm'
})(EditUserForm);

export default connect(null,(dispatch)=>({
    onSubmit(values){
        return appApi.user.saveUser(values).then((response) => {
            if (response.status === 'ok') {
                dispatch(modalClose());
                dispatch(updateUsers());
                return;
            }
            throw new SubmissionError({
                _error: response.error,
                ...response.errors
            })
        });
    }
}))(EditUserForm);
