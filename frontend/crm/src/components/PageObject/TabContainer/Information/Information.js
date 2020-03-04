import React, {useEffect} from 'react'
import {Tab, Grid, Image, Icon, Button, Form} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../../../components/Breadcrumb/reducer";
import style from './Information.module.css'

const Information = (props) => {

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
            <Grid columns={16} container className={style.container}>
                <Grid.Row>
                    <Grid.Column width={4} className={style.leftColumn}>
                        <div>
                            <div className={style.imageMainBlock}>
                                <div className={style.imageBackground}/>
                                <Image src='/img/home_main.png'/>
                                <Icon className={style.iconDeleteImage} name='window close outline'/>
                            </div>
                            <Button className={style.addImageButton}>
                                <Icon name='image outline'/>
                                Изменить основную фотографию
                            </Button>
                        </div>
                        <div>
                            <div className={style.dopImageContainer}>
                                <div className={style.imageDopBlock}>
                                    <div className={style.imageBackground}/>
                                    <Image src='/img/home_child.png'/>
                                    <Icon className={style.iconDeleteImage} name='window close outline'/>
                                </div>
                                <div className={style.imageDopBlock}>
                                    <div className={style.imageBackground}/>
                                    <Image src='/img/home_child.png'/>
                                    <Icon className={style.iconDeleteImage} name='window close outline'/>
                                </div>
                                <div className={style.imageDopBlock}>
                                    <div className={style.imageBackground}/>
                                    <Image src='/img/home_child.png'/>
                                    <Icon className={style.iconDeleteImage} name='window close outline'/>
                                </div>
                                <div className={style.imageDopBlock}>
                                    <div className={style.imageBackground}/>
                                    <Image src='/img/home_child.png'/>
                                    <Icon className={style.iconDeleteImage} name='window close outline'/>
                                </div>
                                <div className={style.imageDopBlock}>
                                    <div className={style.imageBackground}/>
                                    <Image src='/img/home_child.png'/>
                                    <Icon className={style.iconDeleteImage} name='window close outline'/>
                                </div>
                            </div>
                            <Button className={style.addImageButton}>
                                <Icon name='image outline'/>
                                Добавить фотографии
                            </Button>
                        </div>
                    </Grid.Column>
                    <Grid.Column width={12} className={style.rightColumn}>
                        <Form>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='Название объекта' placeholder='Название объекта'/>
                                <Form.Field>
                                    <label>Статус объекта</label>
                                    <Form.Group widths='equal' className={style.statusGroup}>
                                    <Form.Checkbox
                                        label='Предварительный'
                                        value='1'
                                    />
                                    <Form.Checkbox
                                        label='В работе'
                                        value='2'
                                    />
                                    <Form.Checkbox
                                        label='Закрытый'
                                        value='3'
                                    />
                                    </Form.Group>
                                </Form.Field>
                            </Form.Group>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='Дата' placeholder='Дата'/>
                                <Form.Input fluid label='Площадь' placeholder='Площадь'/>
                                <Form.Input fluid label='Сумма' placeholder='Сумма'/>
                                <Form.Input fluid label='Схема' placeholder='Схема'/>
                            </Form.Group>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='Этажи' placeholder='Этажи'/>
                                <Form.Input fluid label='Радиаторы' placeholder='Радиаторы'/>
                                <Form.Input fluid label='Котельная' placeholder='Котельная'/>
                                <Form.Input fluid label='Теплый пол' placeholder='Теплый пол'/>
                            </Form.Group>
                            <Form.Group widths='equal'>
                                <Form.Input fluid label='Адрес объекта' placeholder='Адрес объекта'/>
                            </Form.Group>
                            <Image src='/img/object_map.png' className={style.objectMap}/>
                            <Button positive className={style.saveButton}>Сохранить</Button>
                        </Form>
                    </Grid.Column>
                </Grid.Row>
            </Grid>
    );
};

export default connect(
    state => ({}),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
    })
)(Information);
