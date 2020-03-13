import React from 'react';
import {Form} from "semantic-ui-react";
import {Field, reduxForm, SubmissionError} from 'redux-form';
import {connect} from "react-redux";
import Input from "../../components/FormElements/Input";
import Select from "../../components/FormElements/Select";
import {setIsSaveProduct} from "./../reducer";

let ProductForm = (props) => {
    const {handleSubmit, submitting} = props;
    return (
        <Form onSubmit={handleSubmit} loading={submitting}>
            <Field
                name="name"
                component={Input}
                label="Название товара"
                type="text"
                placeholder="Название товара"
            />
            <Field
                name="product_analog"
                component={Select}
                label="Товарная группа"
                type="text"
                placeholder="Товарная группа"
                options={[]}
            />
            <Field
                name="vendor_code"
                component={Input}
                label="Артикул"
                type="text"
                placeholder="Артикул"
            />
            <Field
                name="brand"
                component={Input}
                label="Бренд"
                type="text"
                placeholder="Бренд"
            />
            <Field
                name="rrc"
                component={Input}
                label="РРЦ"
                type="text"
                placeholder="РРЦ"
            />
            <Field
                name="discount"
                component={Input}
                label="Скидка"
                type="text"
                placeholder="Скидка"
            />
            <Field
                name="amount"
                component={Input}
                label="Цена со скидкой"
                type="text"
                placeholder="Цена со скидкой"
            />
            <Field
                name="property_analog"
                component={Select}
                label="Свойство аналогичности"
                type="text"
                placeholder="Свойство аналогичности"
                options={[]}
            />
        </Form>
    );
};

ProductForm = reduxForm({
    form: 'createProductForm'
})(ProductForm);

ProductForm = connect(null,(dispatch)=>({
    onSubmit(values) {
        return appApi.product.save(values).then((response) => {
            if (response.status === 'ok') {
                dispatch(setIsSaveProduct(true));
                return;
            }
            throw new SubmissionError({
                _error: response.error,
                ...response.errors
            })
        });
    }
}))(ProductForm);

export default ProductForm;
