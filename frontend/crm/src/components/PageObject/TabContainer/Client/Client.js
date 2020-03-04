import React, {useEffect} from 'react'
import {Button, Form, Grid, Tab} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../../../components/Breadcrumb/reducer";
import style from './Client.module.css'

const Client = (props) => {

    const {
        onSetPath,

    } = props;

    useEffect(() => {
        onSetPath([
            {link: '/', label: 'Главная', active: false},
            {link: '/', label: 'Объекты и сметы', active: false},
            {link: '/object', label: 'Новый объект', active: true}
        ]);
    });

    return (
            <Form>
                <Grid columns={16} divided>
                    <Grid.Row>
                        <Grid.Column width={10}>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='ФИО руководителя' placeholder='ФИО руководителя'/>
                                <Form.Input fluid label='Должность руководителя' placeholder='Должность руководителя'/>
                            </Form.Group>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='Телефон 1' placeholder='Телефон 1'/>
                                <Form.Input fluid label='Телефон 2' placeholder='Телефон 2'/>
                                <Form.Input fluid label='E-mail' placeholder='E-mail'/>
                            </Form.Group>
                        </Grid.Column>
                        <Grid.Column width={6}>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='ФИО прораба' placeholder='ФИО прораба'/>
                            </Form.Group>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='Телефон прораба' placeholder='Телефон прораба'/>
                                <Form.Input fluid label='E-mail прораба' placeholder='E-mail прораба'/>
                            </Form.Group>
                        </Grid.Column>
                    </Grid.Row>
                </Grid>
                <Form.Group widths='equal'>
                    <Form.Input fluid label='Название организации' placeholder='Название организации'/>
                    <Form.Input fluid label='На основании ( устава / указа / приказа )' placeholder='На основании ( устава / указа / приказа )'/>
                </Form.Group>
                <Form.Group widths='equal'>
                    <Form.Input fluid label='ИНН' placeholder='ИНН'/>
                    <Form.Input fluid label='Расчетный счет' placeholder='Расчетный счет'/>
                    <Form.Input fluid label='Корреспондентский счет' placeholder='Корреспондентский счет'/>
                </Form.Group>
                <Form.Group widths='equal'>
                    <Form.Input fluid label='Юридический адрес' placeholder='Юридический адрес'/>
                </Form.Group>
                <Form.Group widths='equal'>
                    <Form.Input fluid label='Почтовый адрес' placeholder='Почтовый адрес'/>
                </Form.Group>
                <div className={style.buttonBlock}>
                    <Button positive className={style.saveButton}>Сохранить</Button>
                </div>
            </Form>
    );
};

export default connect(
    state => ({}),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
    })
)(Client);
