import React from 'react'
import style from './DeleteCategoryModal.module.css'
import {connect} from "react-redux";
import {Button, Header, Message, Modal, Segment} from "semantic-ui-react";
import {deleteCategory, modalClose} from "./reducer";
import {getIsShow, getIsLoading, getCategoryData, getError} from "./selectors";

const DeleteCategoryModal = (props) => {

    const {
        isShow,
        isLoading,
        categoryData,
        modalClose,
        deleteCategory,
        error
    } = props;

    return (
        <Modal size='small' open={isShow} onClose={modalClose} closeIcon>
            <Modal.Header>Удаление</Modal.Header>
            <Modal.Content>
                <Segment loading={isLoading} className={style.container}>
                    {!isLoading && categoryData != null && (
                        <Header as='h3'>Удалить группу "{categoryData.name}"?</Header>)}
                    { !isLoading && error && (
                        <Message color='red'>{error}</Message>
                    )}
                </Segment>
            </Modal.Content>
            <Modal.Actions>
                <Button negative onClick={modalClose}>Отмена</Button>
                <Button positive disabled={isLoading} onClick={deleteCategory}>Удалить</Button>
            </Modal.Actions>
        </Modal>
    );
};

export default connect(
    state => ({
        isShow: getIsShow(state),
        isLoading: getIsLoading(state),
        categoryData: getCategoryData(state),
        error: getError(state),
    }), {
        modalClose,
        deleteCategory,
    })(DeleteCategoryModal);

