import React from 'react'
import {Breadcrumb as BreadcrumbSemantic} from 'semantic-ui-react'
import {connect} from 'react-redux'
import {Link} from "react-router-dom";

const Breadcrumb = (props) => {
    const list = props.list;
    let elements = [];
    let element;
    for (let i = 0; i < list.length; i++) {
        let item = list[i],
        key = i;
        if (item.active) {
            element = (<BreadcrumbSemantic.Section key={key} active>{item.label}</BreadcrumbSemantic.Section>);
        } else {
            element = (<BreadcrumbSemantic.Section key={key} as={Link} to={item.link}>{item.label}</BreadcrumbSemantic.Section>);
        }
        elements.push(element);
        if (i < list.length - 1) {
            elements.push(<BreadcrumbSemantic.Divider key={key+'_d'}>/</BreadcrumbSemantic.Divider>);
        }
    }
    return (
        <BreadcrumbSemantic>
            {elements}
        </BreadcrumbSemantic>
    )
};

export default connect(state => ({
    list: state.breadcrumb
}), dispatch => ({}))(Breadcrumb);