import React,{useEffect} from 'react'
import style from "./EditUser.module.css";
import {Button, Modal, Segment} from "semantic-ui-react";
import EditUserForm from "./EditUserForm/EditUserForm";
import {connect} from "react-redux";
import {getUserData, modalClose} from "./reducer";
import {getIsLoading, getIsShow, getUserDate, getUserId} from "./selectors";
import { isSubmitting,submit} from 'redux-form';

const EditUser = (props) => {

    const {
        getUserData,
        isShow,
        isLoading,
        modalClose,
        submitting,
        userId,
        userDate,
        onSave
    } = props;

    useEffect(()=>{
        getUserData();
    },[userId]);

    return (
        <Modal size='small' open={isShow} onClose={modalClose} closeIcon className={style.editUserModal}>
            <Modal.Header>Редактировать пользователя</Modal.Header>
            <Modal.Content>
                <Segment loading={isLoading}>
                    { userDate!=null && <EditUserForm initialValues={userDate} userImage={userDate.image} userId={userDate.id}/>}
                </Segment>
            </Modal.Content>
            <Modal.Actions>
                <Button negative onClick={modalClose}>Отмена</Button>
                <Button positive disabled={submitting} onClick={onSave}>Сохранить</Button>
            </Modal.Actions>
        </Modal>
    );
};

export default connect((state) => ({
    isShow: getIsShow(state),
    isLoading: getIsLoading(state),
    userId:getUserId(state),
    submitting: isSubmitting('adminEditUserForm')(state),
    userDate:getUserDate(state),
}), {
    modalClose,
    getUserData,
    onSave:()=>(dispatch)=>{
        dispatch(submit('adminEditUserForm'))
    }
})(EditUser);