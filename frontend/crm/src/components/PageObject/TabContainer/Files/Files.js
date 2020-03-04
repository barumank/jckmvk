import React, {useEffect} from 'react'
import { Tab} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../../../components/Breadcrumb/reducer";
import FileGroup from "./FileGroup/FileGroup";
import style from './Files.module.css';



const Files = (props) => {

    const {
        onSetPath,

    } = props;

    useEffect(() => {
        onSetPath([
            {link: '/', label: 'Главная', active: false},
            {link:'/',label:'Объекты и сметы',active:false},
            {link:'/object',label:'Новый объект',active:true}
        ]);
    });

    return (
        <>
            <div className={style.statusContainer}>
            <FileGroup name='В работе' statusClass={style.inProgress} />
            <FileGroup name='Документы' statusClass={style.documents} />
            <FileGroup name='Финансы' statusClass={style.finance} />
            <FileGroup name='Быстрые' statusClass={style.quick} />
            <FileGroup name='Разное' statusClass={style.other} />
            </div>
        </>
    );
};

export default connect(
    /*state => ({
        test:1
    })*/null,
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
    })
)(Files);
