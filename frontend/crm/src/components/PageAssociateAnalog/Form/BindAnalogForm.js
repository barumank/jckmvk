import {Button, Form, Icon, Menu} from "semantic-ui-react";
import {NavLink} from "react-router-dom";
import style from "../PageAssociateAnalog.module.css";
import {Field, reduxForm, SubmissionError} from "redux-form";
import Select2 from "../componets/Select2/Select2";
import WrappedDropdown from "../componets/Dropdown/Dropdown";
import React, {useEffect} from "react";
import {connect} from "react-redux";
import {onClearValues, onSearchChangeProduct, onSearchAnalogChangeProduct, fetchProductAttributeGroups} from "./reducer";
import {getProductAnalogOptions, getProductOptions, getProductAttributeGroupOptions} from "./selectors";

let BindAnalogForm = (props) => {
    const {
        handleSubmit,
        productOptions,
        productAnalogOptions,
        productAttributeGroupOptions,
        onSearchChangeProduct,
        onSearchAnalogChangeProduct,
        fetchProductAttributeGroups
    } = props;

    useEffect(() => {
        fetchProductAttributeGroups();
    }, []);

    const isActiveMainLink = (match, location) => {
        return ['/associate-analog', '/associate-analog/base'].includes(location.pathname)
    };
    return (
        <Form onSubmit={handleSubmit}>
            <Menu attached='top' tabular>
                <Menu.Item as={NavLink} isActive={isActiveMainLink} className={style.tabItemColumn}
                           to='/associate-analog'>
                    <div>Основной товар</div>
                    <Field
                        name="product_id"
                        component={Select2}
                        options={productOptions}
                        onSearchChange={onSearchChangeProduct}
                        search
                        selection
                    />
                </Menu.Item>
                <Menu.Item as={NavLink} className={style.tabItemColumn} to='/associate-analog/analog'>
                    <div>Товар анатлог</div>
                    <Field
                        name="analog_id"
                        component={Select2}
                        options={productAnalogOptions}
                        onSearchChange={onSearchAnalogChangeProduct}
                        search
                        selection
                    />
                </Menu.Item>
                <Menu.Item as={NavLink} className={style.tabItemColumn} to='/associate-analog/property'>
                    <div>Свойство аналогичнсти</div>
                    <Field
                        name="group_id"
                        component={WrappedDropdown}
                        options={productAttributeGroupOptions}
                    />
                </Menu.Item>
                <Menu.Item>
                    <Button type='submit' positive className={style.bindButton}>
                        <Icon name='checkmark'/> Связать товар
                    </Button>
                </Menu.Item>
            </Menu>
        </Form>
    );
};

BindAnalogForm =  reduxForm({
    form: 'bindAnalogForm'
})(BindAnalogForm);

export default connect(
    state => ({
        productOptions: getProductOptions(state),
        productAnalogOptions: getProductAnalogOptions(state),
        productAttributeGroupOptions: getProductAttributeGroupOptions(state)
    }),
    dispatch => ({
        onSubmit(values) {
            return appApi.product.saveAnalogGroup(values).then((response) => {
                if (response.status === 'ok') {
                    dispatch(onClearValues());
                    return;
                }
                throw new SubmissionError({
                    _error: response.error,
                    ...response.errors
                })
            });
        },
        onSearchChangeProduct: (event, data) => {
            dispatch(onSearchChangeProduct(event, data));
        },
        onSearchAnalogChangeProduct: (event, data) => {
            dispatch(onSearchAnalogChangeProduct(event, data));
        },
        fetchProductAttributeGroups: (event, data) => {
            dispatch(fetchProductAttributeGroups(event, data));
        }
    })
)(BindAnalogForm);