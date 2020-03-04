import React from 'react'
import {Header, Segment} from "semantic-ui-react";
import style from "./ProductContainer.module.css";
import {Scrollbars} from "react-custom-scrollbars";

const ProductContainer = (props) => {
    const {loading, children,categoryName} = props;

    const renderThumb = ({style, ...props}) => {
        style = {...style, width: '14px', borderRadius: '3px', backgroundColor: '#60bab5'};
        return (
            <div
                style={{...style}}
                {...props}/>
        );
    };
    const renderTrack = ({style, ...props}) => {
        style = {
            ...style,
            width: '16px',
            bottom: 0,
            top: 0,
            right: '0',
            border: '1px solid #dededf',
            backgroundColor: '#ffffff'
        };
        return (
            <div
                style={{...style}}
                {...props}/>
        );
    };
    const renderView = ({style, ...props}) => {
        style = {...style, paddingRight: '16px'};
        return (
            <div
                style={{...style}}
                {...props}/>
        );
    };

    return (
        <Segment className={style.productContainer} loading={loading}>
            <Header as={'h3'}>
                {categoryName}
            </Header>
            <div className={style.tableContainer}>
            <Scrollbars
                renderThumbVertical={renderThumb}
                renderTrackVertical={renderTrack}
                renderView={renderView}
                renderTrackHorizontal={() => (<div style={{display: 'none'}}/>)}
                renderThumbHorizontal={() => (<div/>)}
                autoHeight
                autoHeightMin={0}
                autoHeightMax={400}
            >
                {children}
            </Scrollbars>
            </div>
        </Segment>

    );
};

export default ProductContainer;