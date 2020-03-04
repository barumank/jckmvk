import React, {useEffect} from 'react'
import style from './EditBid.module.css';
import {Button, Grid, Modal, Segment} from "semantic-ui-react";
import {connect} from "react-redux";
import {getIsLoading, getIsShow, getUserDate, getUserId} from "./selectors";
import {setBidAccept, getUserData, modalClose, setBidCancel} from "./reducer";
import * as moment from "moment";


const User = (props) => {
    const {user} = props;
    return (
        <Grid>
            <Grid.Row>
                <Grid.Column width='2'>
                    <strong>Имя:</strong>
                </Grid.Column>
                <Grid.Column width='14'>
                    {user.name}
                </Grid.Column>
            </Grid.Row>
            <Grid.Row>
                <Grid.Column width='2'>
                    <strong>Email:</strong>
                </Grid.Column>
                <Grid.Column width='14'>
                    {user.email}
                </Grid.Column>
            </Grid.Row>
            <Grid.Row>
                <Grid.Column width='2'>
                    <strong>Дата:</strong>
                </Grid.Column>
                <Grid.Column width='14'>
                    {moment(user.date_create, 'YYYY-MM-DD HH:mm:ss').format('DD.MM.YYYY')}
                </Grid.Column>
            </Grid.Row>
        </Grid>
    );
};


const EditBid = (props) => {
    const {
        getUserData,
        isShow,
        isLoading,
        modalClose,
        userData,
        userId,
        setBidAccept,
        setBidCancel
    } = props;


    useEffect(() => {
        getUserData();
    }, [userId]);

    return (<Modal size='tiny' open={isShow} onClose={modalClose} closeIcon className={style.editBidModal}>
        <Modal.Header>Заявка от пользователя</Modal.Header>
        <Modal.Content>
            <Segment loading={isLoading}>
                { userData !==null &&  <User user={userData}/>}
            </Segment>
        </Modal.Content>
        <Modal.Actions>
            <Button negative onClick={setBidCancel}>Отменить заявку</Button>
            <Button positive onClick={setBidAccept}>Принять заявку </Button>
        </Modal.Actions>
    </Modal>)
};

export default connect(
    (state) => ({
        isLoading: getIsLoading(state),
        userData: getUserDate(state),
        isShow: getIsShow(state),
        userId: getUserId(state)
    }),
    {
        modalClose,
        getUserData,
        setBidAccept,
        setBidCancel
    }
)(EditBid);