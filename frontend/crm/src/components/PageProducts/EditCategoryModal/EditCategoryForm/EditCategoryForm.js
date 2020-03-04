import React from 'react'
import style from './EditCategoryForm.module.css'
import {Field, reduxForm, SubmissionError,change} from "redux-form";
import {Button, Divider, Form, Grid, Image} from "semantic-ui-react";
import Input from "../../../components/FormElements/Input";
import {connect} from "react-redux";
import {modalClose, updateList} from "../reducer";
import TreeView from "../../../components/TreeView/TreeView";
import FileInput from "../../../components/FormElements/File";
import {getCategories, getCategoryId, getCategoryImage, getParentCategoryId, getType, getUserId} from "../selectors";

let EditCategoryForm = (props) => {

    const {
        rootNodeName,
        categories,
        parentCategoryId,
        categoryId,
        categoryImage,
        onSetParentId,
        userId,
        handleSubmit,
        pristine, reset, submitting, error} = props;

    const onButtonClick = (e) => {
        e.preventDefault();
        document.getElementById('categoryImage').click()
    };

    const onFileChange = (file)=>{
        let reader = new FileReader();
        reader.onloadend = () => {
            document
                .getElementById('categoryLogo')
                .setAttribute('src',reader.result)
        };
        reader.readAsDataURL(file)
    };

    const getCategoryImage = () =>{
        if(!categoryImage){
            return (<Image id='categoryLogo' src='/img/no_image.png'/>)
        }
        return  <Image id='categoryLogo' src={`/upload/users/${userId}/category_images/${categoryImage}`} />
    };

    return (
        <Form onSubmit={handleSubmit} loading={submitting} error={!!error}>
            <Grid columns={16} divided>
                <Grid.Row>
                    <Grid.Column width={4} className={style.leftColumn}>
                        {getCategoryImage()}
                        <Button className={style.uploadPhoto} onClick={onButtonClick}>Загрузить фото</Button>
                    </Grid.Column>
                    <Grid.Column width={12}>
                        <Field
                            component={FileInput}
                            name="image"
                            id='categoryImage'
                            onChange={onFileChange}
                            style={{display:'none'}}
                            accept=".jpg,.png,.jpeg,.bmp"
                        />
                        <Field
                            name="id"
                            type="hidden"
                            component='input'
                        />
                        <Field
                            name="parent_id"
                            type="hidden"
                            component='input'
                        />
                        <Field
                            name="type"
                            type="hidden"
                            component='input'
                        />
                        <TreeView nodeList={categories}
                                  selectedId={categoryId}
                                  selectedParentId={parentCategoryId}
                                  rootNodeName={rootNodeName}
                                  title='Выбор группы'
                                  label='Базовая группа'
                                  selectButtonName='Выбрать группу'
                                  onSelectDir={(data) => onSetParentId(data.id)}
                        />
                        <Field
                            name="name"
                            component={Input}
                            label='Название'
                            type="text"
                            placeholder="Название"
                        />
                    </Grid.Column>
                </Grid.Row>
            </Grid>
        </Form>
    );
};

EditCategoryForm = reduxForm({
    form: 'editCategoryForm'
})(EditCategoryForm);

EditCategoryForm = connect(state=>({
    categories: getCategories(state),
    categoryId: getCategoryId(state),
    parentCategoryId: getParentCategoryId(state),
    categoryImage:getCategoryImage(state),
    userId:getUserId(state),
    rootNodeName:((state)=>{
        let type = getType(state);
        let name = 'Диск';
        let parseType = Number.parseInt(type);
        if (appApi.category.TYPE_MAP.has(parseType)) {
            name = appApi.category.TYPE_MAP.get(parseType);
        }
        return name;
    })(state)
}), (dispatch) => ({
    onSubmit(values) {
        return appApi.category.save(values).then((response) => {
            if (response.status === 'ok') {
                dispatch(modalClose());
                dispatch(updateList());
                return;
            }
            throw new SubmissionError({
                _error: response.error,
                ...response.errors
            })
        });
    },
    onSetParentId(id){
      dispatch(change('editCategoryForm','parent_id',id));
    }
}))(EditCategoryForm);

export default EditCategoryForm;

