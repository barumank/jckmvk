import React, {useEffect} from 'react'
import style from "./PageOrganization.module.css";
import {Button, Divider, Form, Grid, Header, Image, Menu, Message, Segment, Table} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../components/Breadcrumb/reducer";
import OrganizationForm from "./OrganizationForm/OrganizationForm";
import LegalEntities from "./LegalEntities/LegalEntities";
import UserList from "./UserList/UserList";

const PageOrganization = (props) => {
    const {setPath, image} = props;

    useEffect(() => {
        setPath([
            {link: '/', label: 'Главная', active: false},
            {link: '/organization', label: 'Организация', active: true}
        ]);
    }, []);

    return (
        <Segment className={style.container} loading={false}>
            <Segment>
                <Header as='h4'>Организация</Header>
                <Divider/>
                <Grid columns='two' divided>
                    <Grid.Row>
                        <Grid.Column width='12'>
                            <OrganizationForm/>
                        </Grid.Column>
                        <Grid.Column width='4'>
                            <LegalEntities/>
                        </Grid.Column>
                    </Grid.Row>
                </Grid>
            </Segment>
            <UserList/>
        </Segment>
    )
};

export default connect(() => {
}, {
    setPath
})(PageOrganization);