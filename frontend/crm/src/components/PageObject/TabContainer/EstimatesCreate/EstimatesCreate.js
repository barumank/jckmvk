import React, {useEffect} from 'react'
import {Button, Icon, Tab} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../../../components/Breadcrumb/reducer";
import style from './EstimatesCreate.module.css';
import EstimatesTemplate from "../../../EstimatesTempleate/EstimatesTemplate";

const EstimatesCreate = (props) => {

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
        <>
            <div className={style.control}>
                <div>
                    <Button><Icon name='upload'/> Загрузить шаблон</Button>
                    <Button><Icon name='plus circle'/> Создать новый шаблон</Button>
                </div>
                <div>
                    <Button className={style.buttonCreateOffer}><Icon name='file alternate'/> Загрузить шаблон</Button>
                    <Button positive>Создать новый шаблон</Button>
                </div>
            </div>
            <div className={style.templateContainer}>
                <EstimatesTemplate/>
                <EstimatesTemplate/>
                <EstimatesTemplate/>
                <EstimatesTemplate/>
            </div>
        </>
    );
};

export default connect(
    state => ({}),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
    })
)(EstimatesCreate);
