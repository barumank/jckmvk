import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import style from './PageProduct.module.css'
import {setPath} from '../components/Breadcrumb/reducer'
import {Button, Form, Grid, Header, Icon, Image, Message, Segment} from "semantic-ui-react";
import AnalogsTemplate from "./AnalogsTemplate/AnalogsTemplate";
import ProductForm from "./ProductForm/ProductForm";
import {isSubmitting, submit} from "redux-form";
import {isSave} from "./selectors";
import {setIsSaveProduct} from "./reducer";
import {Link} from "react-router-dom";
const PageProduct = (props) => {

    const {onSetPath, onSave, isSave, setIsSaveProduct} = props;
    useEffect(() => {
        onSetPath([
            {link: '/', label: 'Главная', active: false},
            {link: '/products', label: 'Товары', active: false},
            {link: '/product/new', label: 'Товар', active: true},
        ]);
        if (isSave) {
            return () => setIsSaveProduct(false);
        }
    },[isSave]);

    return (
        <>
            <Segment className={style.containerPage}>
                <div className={style.topContainer}>
                    <Header as='h4'>Товар</Header>
                    <div className={style.buttonGroup}>
                        <Button as={Link} to='/associate-analog' className={style.attachAnalog}><Icon name='attach'/> Привязать аналог</Button>
                        <Button className={style.saveProduct} onClick={onSave}>Сохранить</Button>
                    </div>
                </div>

                <Grid columns={16} container divided className={style.container}>
                    <Grid.Row>
                        <Grid.Column width={4} className={style.leftColumn}>
                            <div>
                                <div className={style.imageMainBlock}>
                                    <div className={style.imageBackground}/>
                                    <Image src='/img/product.png'/>
                                    <Icon className={style.iconDeleteImage} name='window close outline'/>
                                </div>
                                <Button className={style.addImageButton}>
                                    <Icon name='image outline'/>
                                    Изменить фотографию
                                </Button>
                            </div>
                            <div>
                                {isSave ? (
                                    <Message positive>
                                        <Message.Header>Товар успешно сохранен!</Message.Header>
                                    </Message>
                                ) : (
                                    <ProductForm/>
                                )}
                            </div>
                        </Grid.Column>
                        <Grid.Column width={12} className={style.rightColumn}>
                            <Header as='h4'>Аналоги</Header>
                            <AnalogsTemplate/>
                            <AnalogsTemplate/>
                            <AnalogsTemplate/>
                            <AnalogsTemplate/>
                        </Grid.Column>
                    </Grid.Row>
                </Grid>
            </Segment>
        </>
    );
};

export default connect(state => ({
        submitting: isSubmitting('createProductForm')(state),
        isSave: isSave(state),
    }),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
        onSave: () => {
            dispatch(submit('createProductForm'));
        },
        setIsSaveProduct,
    })
)(PageProduct);
