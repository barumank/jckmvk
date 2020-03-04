import React,{useEffect} from 'react'
import style from "./PageUserSettings.module.css";
import {Header, Segment} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../components/Breadcrumb/reducer";
import SettingsForm from "./SettingsForm/SettingsForm";
import {getUser} from "./selectors";

const PageUserSettings = (props) => {

    const {onSetPath,user} = props;

    useEffect(() => {
        onSetPath([
            {link: '/', label: 'Главная', active: false},
            {link: '/settings', label: 'Настройки профиля', active: true}
        ]);
    }, []);

    return (
        <Segment className={style.container}>
            <Header as='h4'>Настройки профиля</Header>
            <SettingsForm initialValues={user} image={user.image} userId={user.id}/>
        </Segment>
    )
};

export default connect(
    (state)=>({
        user:getUser(state)
    }),
    (dispatch)=>({
        onSetPath(list) {
            dispatch(setPath(list));
        },
    })
)
(PageUserSettings);