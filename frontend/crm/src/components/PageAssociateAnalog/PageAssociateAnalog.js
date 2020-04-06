import React from 'react'
import style from './PageAssociateAnalog.module.css'
import {Menu, Header, Segment, Button, Dropdown, Input, Search, Icon, Form} from "semantic-ui-react";
import {NavLink, Route, Switch} from "react-router-dom";
import MainProduct from "./MainProduct/MainProduct";
import AnalogProduct from "./AnalogProduct/AnalogProduct";
import { connect } from 'react-redux';
import {getPropertyDropdownShow} from './selectors';
import {Field, reduxForm, SubmissionError} from 'redux-form'
import BindAnalogForm from './Form/BindAnalogForm';

const PageAssociateAnalog = (props) => {
    const { propertyDropdownShow } = props;
    const isActiveMainLink = (match, location) => {
        return ['/associate-analog', '/associate-analog/base'].includes(location.pathname)
    };
    return (
        <>
            <Segment>
                <Header as='h4'>Сопоставление аналогов</Header>
                <BindAnalogForm/>
                <Switch>
                    <Route exact path={['/associate-analog', '/associate-analog/base']}>
                        <MainProduct/>
                    </Route>
                    <Route exact path={['/associate-analog/analog', '/associate-analog/analog/base']}>
                        <AnalogProduct/>
                    </Route>
                </Switch>
            </Segment>
        </>
    );
};

export default connect(
    state => ({
        propertyDropdownShow: getPropertyDropdownShow(state)
    }),
    dispatch => ({

    })
)(PageAssociateAnalog);