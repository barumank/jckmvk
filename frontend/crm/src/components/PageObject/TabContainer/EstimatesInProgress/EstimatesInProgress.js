import React, {useEffect} from 'react'
import {Tab} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../../../components/Breadcrumb/reducer";

const EstimatesInProgress = (props) => {

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
        </>
    );
};

export default connect(
    state => ({
    }),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
    })
)(EstimatesInProgress);
