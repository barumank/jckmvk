import React from 'react'
import {Field, reduxForm, SubmissionError} from "redux-form";
import {Button, Form, Grid, Header, Image, Message, Segment} from "semantic-ui-react";
import {connect} from "react-redux";
import style from './OrganizationForm.module.css';
import Input from "../../components/FormElements/Input";
import FileInput from "../../components/FormElements/File";
import {setCurrentUser} from "../../components/Auth/reducer";

let OrganizationForm = (props) => {

    const {image, userId, handleSubmit, pristine, reset, submitting, error} = props;

    const onButtonClick = (e) => {
        e.preventDefault();
        document.getElementById('userImage').click()
    };

    const onFileChange = (file) => {
        let reader = new FileReader();
        reader.onloadend = () => {
            document.getElementById('userLogo')
                .setAttribute('src', reader.result)
        };
        reader.readAsDataURL(file)
    };

    const getUserImage = () => {
        if (image === null) {
            return (<Image id='userLogo' src='/img/no_image.png'/>)
        }
        return <Image id='userLogo' src={`/upload/users/${userId}/${image}`}/>
    };

    return (
        <Form onSubmit={handleSubmit} loading={submitting} error={!!error}>
            <Header as='h5' textAlign='right'>Основная информация об организации</Header>
            <Grid columns='two' divided>
                <Grid.Row>
                    <Grid.Column width='5' className={style.logoColumn}>
                        <div>
                            {getUserImage()}
                        </div>
                        <Button onClick={onButtonClick} className={style.uploadPhoto}>Загрузить фото</Button>
                        <Field
                            component={FileInput}
                            name="image"
                            id='userImage'
                            onChange={onFileChange}
                            style={{display: 'none'}}
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
                            name="name"
                            component={Input}
                            label='Название организации'
                            type="text"
                            placeholder="Название организации"
                        />
                        <Field
                            name="inn"
                            component={Input}
                            label='ИНН'
                            type="text"
                            placeholder="ИНН"
                        />
                        <Field
                            name="legal_address"
                            component={Input}
                            label='Юридический адрес'
                            type="text"
                            placeholder="Юридический адрес"
                        />
                        <Field
                            name="postal_address"
                            component={Input}
                            label='Почтовый адрес'
                            type="text"
                            placeholder="Почтовый адрес"
                        />
                        <Field
                            name="correspondent_account"
                            component={Input}
                            label='Корреспондентский счет'
                            type="text"
                            placeholder="Корреспондентский счет"
                        />
                        <Field
                            name="payment_account"
                            component={Input}
                            label='Расчётный счёт'
                            type="text"
                            placeholder="Расчётный счёт"
                        />
                        <Field
                            name="name_director"
                            component={Input}
                            label='ФИО руководителя'
                            type="text"
                            placeholder="ФИО руководителя"
                        />
                        <Field
                            name="position_director"
                            component={Input}
                            label='должность руководителя'
                            type="text"
                            placeholder="Имя"
                        />
                        <Field
                            name="by_virtue"
                            component={Input}
                            label='На основании …'
                            type="text"
                            placeholder="На основании …"
                        />
                        <Field
                            name="Email"
                            component={Input}
                            label='Email'
                            type="text"
                            placeholder="email"
                        />
                        <Field
                            name="phone"
                            component={Input}
                            label='Телефон'
                            type="text"
                            placeholder="Телефон"
                        />

                        {error && (<Message error content={error}/>)}
                        <div className={style.buttonGroup}>
                            <Button negative>Отмена</Button>
                            <Button positive disabled={submitting} >Сохранить</Button>
                        </div>
                    </Grid.Column>
                </Grid.Row>
            </Grid>

        </Form>
    );
};

OrganizationForm = reduxForm({
    form: 'organizationForm'
})(OrganizationForm);

export default connect(null, (dispatch) => ({
    onSubmit(values) {
        /*return appApi.user.saveSettings(values).then((response) => {
            if (response.status === 'ok') {
                dispatch(setCurrentUser(response.data.user));
                return;
            }
            throw new SubmissionError({
                _error: response.error,
                ...response.errors
            })
        });*/
    }
}))(OrganizationForm);
