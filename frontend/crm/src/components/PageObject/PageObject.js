import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import style from './PageObject.module.css'

import {Search, Header, Segment, Tab, Button, Icon, Divider, Form, Checkbox} from "semantic-ui-react";
import {Link} from "react-router-dom";
import TabContainer from "./TabContainer/TabContainer";

const PageObject = (props) => {

    return (
        <>
            <Segment className={style.panel}>
                <Header as='h4'>Объекты и сметы</Header>
                <div className={style.buttonContainer}>
                    <Button><Icon name='attach'/> Привязать объект к папке</Button>
                    <Button><Icon name='folder open'/> Создать папку объекта</Button>
                    <Button as={Link} to='/objects/delete'><Icon name='trash alternate'/> Корзина объектов</Button>
                </div>
            </Segment>
            <Segment className={style.container}>
                <TabContainer/>
            </Segment>
        </>
    );
};

export default connect(
    state => ({}),
    dispatch => ({
    })
)(PageObject);
