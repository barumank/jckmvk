import React from 'react'
import {Scrollbars} from "react-custom-scrollbars";
import {Segment} from "semantic-ui-react";
import style from "./CategoryContainer.module.css";

const CategoryContainer = (props) => {

    const {loading, children} = props;

    const renderThumb = ({style, ...props}) => {
        style = {...style, width: '14px', borderRadius: '3px',backgroundColor: '#60bab5'};
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
        <Segment className={style.categoryContainer} loading={loading}>
            <Scrollbars
                renderThumbVertical={renderThumb}
                renderTrackVertical={renderTrack}
                renderView={renderView}
                renderTrackHorizontal={() => (<div style={{display: 'none'}}/>)}
                renderThumbHorizontal={() => (<div/>)}
                autoHeight={false}
                autoHeightMin={0}
                autoHeightMax={468}
            >
                {children}
            </Scrollbars>
        </Segment>
    );
};

export default CategoryContainer