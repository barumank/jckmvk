import React from 'react'
import style from './EditCategoryModal.module.css'
import {connect} from "react-redux";
import {
    getCategoryData,
    getIsLoading,
    getIsShow, getType,
} from './selectors'
import {Button, Modal, Segment} from "semantic-ui-react";
import {modalClose} from "./reducer";
import EditCategoryForm from "./EditCategoryForm/EditCategoryForm";
import {isSubmitting, submit} from "redux-form";

const EditCategoryModal = (props) => {

    const {
        isShow,
        isLoading,
        categoryData,
        modalClose,
        submitting,
        type,
        onSave
    } = props;

    const getTitle = () => {
        if (isLoading) {
            return '';
        }
        return categoryData !== null ? 'Редактирование группы' : 'Добавление группы'
    };

    const getForm = () => {
        if (isLoading) {
            return (<></>)
        }
        if (categoryData !== null) {
            return (<EditCategoryForm initialValues={categoryData}/>);
        } else {
            return (<EditCategoryForm initialValues={{type}}/>);
        }
    };

    return (
        <Modal size='small' open={isShow} onClose={modalClose} closeIcon>
            <Modal.Header>{getTitle()}</Modal.Header>
            <Modal.Content>
                <Segment loading={isLoading} className={style.container}>
                    {getForm()}
                </Segment>
            </Modal.Content>
            <Modal.Actions>
                <Button negative onClick={modalClose}>Отмена</Button>
                <Button positive disabled={submitting} onClick={onSave}>Сохранить</Button>
            </Modal.Actions>
        </Modal>
    );
};

export default connect(
    state => ({
        isShow: getIsShow(state),
        isLoading: getIsLoading(state),
        categoryData: getCategoryData(state),
        type: getType(state),
        submitting: isSubmitting('editCategoryForm')(state),
    }), {
        modalClose,
        onSave: () => (dispatch) => {
            dispatch(submit('editCategoryForm'))
        }
    })(EditCategoryModal);

